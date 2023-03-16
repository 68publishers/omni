<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class EmailAddress implements ValueObjectInterface
{
    use EmailAddressValueTrait;
}
