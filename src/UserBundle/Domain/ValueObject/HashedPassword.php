<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\NullableStringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class HashedPassword implements ValueObjectInterface
{
    use NullableStringValueTrait;

    public function verify(Password $password): bool
    {
        $nativePassword = $password->toNative();
        $nativeHash = $password->toNative();

        return null !== $nativePassword && null !== $nativeHash && password_verify($nativePassword, $nativeHash);
    }
}
