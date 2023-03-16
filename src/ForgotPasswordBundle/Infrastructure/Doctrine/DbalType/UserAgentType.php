<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\TextType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent;

final class UserAgentType extends TextType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return UserAgent::class;
    }
}
