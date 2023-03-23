<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Template;

interface TemplateInterface
{
    /**
     * Return the original Template representation
     */
    public function unwrap(): mixed;

    /**
     * @param array<string, mixed> $arguments
     */
    public function render(array $arguments): string;
}
