<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddress;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class UserEmailAddressChanged extends AbstractDomainEvent
{
	private UserId $userId;

	private EmailAddressInterface $emailAddress;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId                        $userId
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface $emailAddress
	 *
	 * @return static
	 */
	public static function create(UserId $userId, EmailAddressInterface $emailAddress): self
	{
		$event = self::occur($userId->toString(), [
			'email_address' => $emailAddress->value(),
		]);

		$event->userId = $userId;
		$event->emailAddress = $emailAddress;

		return $event;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\Dto\UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddress
	 */
	public function emailAddress(): EmailAddressInterface
	{
		return $this->emailAddress;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->userId = UserId::fromUuid($this->aggregateId()->id());
		$this->emailAddress = EmailAddress::fromValue($parameters['email_address']);
	}
}
