<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

interface CheckEmailAddressExistsInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\EmailAddressNotFoundException
	 */
	public function __invoke(EmailAddressInterface $emailAddress): void;
}
