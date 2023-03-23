<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class Locale implements ValueObjectInterface
{
    use StringValueTrait;
}
