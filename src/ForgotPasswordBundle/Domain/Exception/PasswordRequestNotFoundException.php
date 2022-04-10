<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;

final class PasswordRequestNotFoundException extends DomainException
{
	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId $id
	 *
	 * @return static
	 */
	public static function withId(PasswordRequestId $id): self
	{
		return new self(sprintf(
			'Password request with ID %s not found.',
			$id
		));
	}
}
