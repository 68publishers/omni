<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\NullableStringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\IpAddressException;

final class IpAddress implements ValueObjectInterface
{
    use NullableStringValueTrait {
        __construct as private __stringConstructor;
    }

    protected function __construct(?string $value)
    {
        if (null !== $value && !filter_var($value, FILTER_VALIDATE_IP)) {
            throw IpAddressException::invalidValue($value);
        }

        $this->__stringConstructor($value);
    }
}
