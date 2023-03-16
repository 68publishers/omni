<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;

final class PasswordStatusChangeException extends DomainException
{
    public static function cantCompleteBecauseRequestIsAlreadyFinished(): self
    {
        return new self('Password request can\'t be completed because it is already in a finished state.');
    }

    public static function cantCancelBecauseRequestIsAlreadyFinished(): self
    {
        return new self('Password request can\'t be canceled because it is already in a finished state.');
    }
}
