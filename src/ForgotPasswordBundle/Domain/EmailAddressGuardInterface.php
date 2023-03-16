<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

interface EmailAddressGuardInterface
{
    public function __invoke(PasswordRequestId $passwordRequestId, EmailAddress $emailAddress): void;
}
