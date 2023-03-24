<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;

final class Password implements ValueObjectInterface
{
    use StringValueTrait;

    public function createHashedPassword(PasswordHashAlgorithmInterface $algorithm): HashedPassword
    {
        return HashedPassword::fromNative(
            $algorithm->hash($this->toNative()),
        );
    }
}
