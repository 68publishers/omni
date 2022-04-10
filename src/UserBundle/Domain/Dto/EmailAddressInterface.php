<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Dto;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\StringValueObjectInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface;

interface EmailAddressInterface extends StringValueObjectInterface, ComparableValueObjectInterface
{
}
