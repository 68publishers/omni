<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class MessageBusConfiguration
{
    /**
     * @param string|array<string, mixed>                  $configuration
     * @param array<class-string<MessageHandlerInterface>> $messageHandlerTypes
     */
    private function __construct(
        public readonly string $busName,
        public readonly string|array $configuration,
        public readonly array $messageHandlerTypes,
    ) {}

    /**
     * @param array<string, mixed>                         $configuration
     * @param array<class-string<MessageHandlerInterface>> $messageHandlerTypes
     */
    public static function fromArray(string $busName, array $configuration, array $messageHandlerTypes = []): self
    {
        return new self($busName, $configuration, $messageHandlerTypes);
    }

    /**
     * @param array<class-string<MessageHandlerInterface>> $messageHandlerTypes
     */
    public static function fromFile(string $busName, string $filename, array $messageHandlerTypes = []): self
    {
        return new self($busName, $filename, $messageHandlerTypes);
    }
}
