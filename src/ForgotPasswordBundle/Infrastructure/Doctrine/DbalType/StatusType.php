<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\Status;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractStringValueObjectType;

final class StatusType extends AbstractStringValueObjectType
{
	protected string $dtoClassname = Status::class;
}
