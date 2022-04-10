<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;

interface PasswordRequestRepositoryInterface
{
	/**
	 * @return string|\SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest
	 */
	public function classname(): string;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest $passwordRequest
	 *
	 * @return void
	 */
	public function save(PasswordRequest $passwordRequest): void;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId $id
	 *
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest
	 * @throws \SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestNotFoundException
	 */
	public function get(PasswordRequestId $id): PasswordRequest;
}
