<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectSetTrait;

final class Translations implements ValueObjectInterface
{
    use ValueObjectSetTrait;

    protected static function getItemClassname(): string
    {
        return Translation::class;
    }
}
