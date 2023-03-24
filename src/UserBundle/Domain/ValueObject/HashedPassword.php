<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class HashedPassword implements ValueObjectInterface
{
    use StringValueTrait;

    public function verify(Password $password): bool
    {
        $nativePassword = $password->toNative();
        $nativeHash = $this->toNative();

        return password_verify($nativePassword, $nativeHash);
    }
}
