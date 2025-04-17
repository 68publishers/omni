<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Handler;

use Fmasa\Messenger\DI\HandlerDefinition;
use Nette\DI\Container;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Handler\HandlerDescriptor;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\Stamp\ReceivedStamp;
use function assert;
use function class_implements;
use function class_parents;
use function in_array;
use function is_callable;
use function strrpos;
use function substr_replace;

final class HandlersLocator implements HandlersLocatorInterface
{
    /** @var array<string, iterable<HandlerDefinition>> */
    private array $handlerDefinitions;

    private Container $container;

    /**
     * @param array<string, iterable<HandlerDefinition>> $handlerDefinitions
     */
    public function __construct(array $handlerDefinitions, Container $container)
    {
        $this->handlerDefinitions = $handlerDefinitions;
        $this->container = $container;
    }

    public function getHandlers(Envelope $envelope): iterable
    {
        $seen = [];

        foreach (self::listTypes($envelope) as $type) {
            foreach ($this->handlerDefinitions[$type] ?? [] as $handlerDefinition) {
                $service = $this->container->getService($handlerDefinition->serviceName);
                $handler = [$service, $handlerDefinition->methodName];
                assert(is_callable($handler));

                $options = $handlerDefinition->options;
                $options['alias'] ??= $handlerDefinition->serviceName;

                $handlerDescriptor = new HandlerDescriptor($handler, $options);

                if (!$this->shouldHandle($envelope, $handlerDescriptor)) {
                    continue;
                }

                $name = $handlerDescriptor->getName();

                if (in_array($name, $seen, true)) {
                    continue;
                }

                $seen[] = $name;

                yield $handlerDescriptor;
            }
        }
    }

    /**
     * @return array<string, string>
     */
    public static function listTypes(Envelope $envelope): array
    {
        $class = $envelope->getMessage()::class;

        return [$class => $class]
            + class_parents($class)
            + class_implements($class)
            + self::listWildcards($class)
            + ['*' => '*'];
    }

    /**
     * @return array<string, string>
     */
    private static function listWildcards(string $type): array
    {
        $type .= '\*';
        $wildcards = [];
        while ($i = strrpos($type, '\\', -3)) {
            $type = substr_replace($type, '\*', $i);
            $wildcards[$type] = $type;
        }

        return $wildcards;
    }

    private function shouldHandle(Envelope $envelope, HandlerDescriptor $handlerDescriptor): bool
    {
        $received = $envelope->last(ReceivedStamp::class);

        if (! $received instanceof ReceivedStamp) {
            return true;
        }

        $expectedTransport = $handlerDescriptor->getOption('from_transport');

        if ($expectedTransport === null) {
            return true;
        }

        return $received->getTransportName() === $expectedTransport;
    }
}
