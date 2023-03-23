<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\CompositeValueObjectTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class Translation implements ValueObjectInterface
{
    use CompositeValueObjectTrait;

    public function __construct(
        public readonly Locale $locale,
        public readonly Subject $subject,
        public readonly MessageBody $messageBody,
    ) {}

    protected static function fromNativeFactory(callable $factory): static
    {
        return new self(
            $factory(Locale::class, 'locale'),
            $factory(Subject::class, 'subject'),
            $factory(MessageBody::class, 'messageBody'),
        );
    }
}
