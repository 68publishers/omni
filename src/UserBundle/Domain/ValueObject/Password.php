<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\NullableStringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;

final class Password implements ValueObjectInterface
{
    use NullableStringValueTrait;

    public function createHashedPassword(PasswordHashAlgorithmInterface $algorithm): HashedPassword
    {
        if ($this->isNull()) {
            return HashedPassword::null();
        }

        return HashedPassword::fromNative(
            $algorithm->hash((string) $this->toNative()),
        );
    }
}
