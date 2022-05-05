<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

final class UserEmailAddressChanged extends AbstractDomainEvent
{
	private UserId $userId;

	private EmailAddressInterface $emailAddress;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId                        $userId
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
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
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress
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
