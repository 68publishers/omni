<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\StringValueObjectInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface;

interface IpAddressInterface extends StringValueObjectInterface, ComparableValueObjectInterface
{
}
