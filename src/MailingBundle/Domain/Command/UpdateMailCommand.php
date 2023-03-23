<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use function array_values;

final class UpdateMailCommand implements CommandInterface
{
    /** @var array<string, array{locale: string, subject: ?string, messageBody: ?string}> */
    private array $translations = [];

    public function __construct(
        public readonly string $mailId,
        public readonly ?string $code = null,
    ) {}

    public function withTranslation(string $locale, ?string $subject, ?string $messageBody): self
    {
        $command = clone $this;
        $command->translations[$locale] = ['locale' => $locale, 'subject' => $subject, 'messageBody' => $messageBody];

        return $command;
    }

    /**
     * @return array<int, array{locale: string, subject: ?string, messageBody: ?string}>
     */
    public function getTranslations(): array
    {
        return array_values($this->translations);
    }
}
