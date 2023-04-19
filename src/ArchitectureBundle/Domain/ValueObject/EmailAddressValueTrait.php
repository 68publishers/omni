<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use Nette\Utils\Validators;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidEmailAddressException;

trait EmailAddressValueTrait
{
    use StringValueTrait;

    protected function validate(): void
    {
        $native = $this->toNative();

        if (!Validators::isEmail($native)) {
            throw InvalidEmailAddressException::create($native, static::class);
        }
    }
}
