<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface;

interface CheckEmailAddressExistsInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface $emailAddress
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\EmailAddressNotFoundException
	 */
	public function __invoke(EmailAddressInterface $emailAddress): void;
}
