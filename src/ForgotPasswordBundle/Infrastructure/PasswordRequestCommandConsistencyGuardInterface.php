<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface;

interface PasswordRequestCommandConsistencyGuardInterface extends CommandConsistencyGuardInterface
{
}
