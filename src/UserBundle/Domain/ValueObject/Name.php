<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\CompositeValueObjectTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use function array_filter;
use function implode;

final class Name implements ValueObjectInterface
{
    use CompositeValueObjectTrait;

    public function __construct(
        private readonly Firstname $firstname,
        private readonly Surname $surname,
    ) {}

    protected static function fromNativeFactory(callable $factory): static
    {
        return new self(
            $factory(Firstname::class, 'firstname'),
            $factory(Surname::class, 'surname'),
        );
    }

    public function getFirstname(): Firstname
    {
        return $this->firstname;
    }

    public function getSurname(): Surname
    {
        return $this->surname;
    }

    public function getName(): string
    {
        return implode(
            ' ',
            array_filter(
                [$this->getFirstname()->toNative(), $this->getSurname()->toNative()],
                static fn (string $part): bool => !empty($part),
            ),
        );
    }
}
