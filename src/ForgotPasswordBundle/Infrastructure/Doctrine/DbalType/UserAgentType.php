<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\UserAgent;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractTextValueObjectType;

final class UserAgentType extends AbstractTextValueObjectType
{
	protected string $dtoClassname = UserAgent::class;
}
