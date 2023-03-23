<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\HttpLink;

use Nette\Application\LinkGenerator;
use Nette\Application\UI\InvalidLinkException;
use SixtyEightPublishers\ArchitectureBundle\Application\HttpLink\LinkFactoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Application\HttpLink\LinkInterface;
use SixtyEightPublishers\ArchitectureBundle\Application\HttpLink\UnableToCreateLinkException;
use function array_merge;

final class LinkFactory implements LinkFactoryInterface
{
    /** @var array<string, array{0: string, 1: array<string, mixed>}> */
    private array $links = [];

    public function __construct(
        private readonly LinkGenerator $linkGenerator,
    ) {}

    /**
     * @param array<string, mixed> $staticParameters
     */
    public function registerLink(string $name, string $destination, array $staticParameters = []): void
    {
        $this->links[$name] = [$destination, $staticParameters];
    }

    /**
     * @throws InvalidLinkException
     */
    public function create(LinkInterface $link): string
    {
        if (!isset($this->links[$link->getName()])) {
            throw UnableToCreateLinkException::create($link);
        }

        [$destination, $staticParameters] = $this->links[$link->getName()];

        return $this->linkGenerator->link($destination, array_merge($staticParameters, $link->getParameters()));
    }
}
