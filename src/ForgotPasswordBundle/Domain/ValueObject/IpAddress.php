<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\IpAddressException;

final class IpAddress implements ValueObjectInterface
{
    use StringValueTrait;

    protected function validate(): void
    {
        $value = $this->toNative();

        if (!filter_var($value, FILTER_VALIDATE_IP)) {
            throw IpAddressException::invalidValue($value);
        }
    }
}
