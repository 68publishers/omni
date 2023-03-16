<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestNotFoundException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

interface PasswordRequestRepositoryInterface
{
    public function save(PasswordRequest $passwordRequest): void;

    /**
     * @throws PasswordRequestNotFoundException
     */
    public function get(PasswordRequestId $id): PasswordRequest;
}
