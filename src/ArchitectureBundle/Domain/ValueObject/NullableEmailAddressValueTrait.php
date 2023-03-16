<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use Nette\Utils\Validators;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidEmailAddressException;

trait NullableEmailAddressValueTrait
{
    use NullableStringValueTrait {
        __construct as private __nullableStringConstructor;
    }

    protected function __construct(?string $value)
    {
        if (null !== $value && !Validators::isEmail($value)) {
            throw InvalidEmailAddressException::create($value, static::class);
        }

        $this->__nullableStringConstructor($value);
    }
}
