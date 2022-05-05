<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

interface CheckEmailAddressUniquenessInterface
{
	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId                        $userId
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\UserBundle\Domain\Exception\EmailAddressUniquenessException
	 */
	public function __invoke(UserId $userId, EmailAddressInterface $emailAddress): void;
}
