<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use Nette\Utils\Validators;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidEmailAddressException;

trait EmailAddressValueTrait
{
    use StringValueTrait {
        __construct as private __stringConstructor;
    }

    protected function __construct(string $value)
    {
        if (!Validators::isEmail($value)) {
            throw InvalidEmailAddressException::create($value, static::class);
        }

        $this->__stringConstructor($value);
    }
}
