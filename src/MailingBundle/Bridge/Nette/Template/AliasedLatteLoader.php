<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\Template;

use Latte\Loader;

final class AliasedLatteLoader implements Loader
{
    /**
     * @param array<string, string> $aliases
     */
    public function __construct(
        private readonly Loader $inner,
        private readonly array $aliases,
    ) {}

    public function getContent(string $name): string
    {
        return $this->inner->getContent($this->aliases[$name] ?? $name);
    }

    public function isExpired(string $name, int $time): bool
    {
        return $this->inner->isExpired($this->aliases[$name] ?? $name, $time);
    }

    public function getReferredName(string $name, string $referringName): string
    {
        return $this->inner->getReferredName($this->aliases[$name] ?? $name, $referringName);
    }

    public function getUniqueId(string $name): string
    {
        return $this->inner->getUniqueId($this->aliases[$name] ?? $name);
    }
}
