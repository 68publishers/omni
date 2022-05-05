<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class UserUsernameChanged extends AbstractDomainEvent
{
	private UserId $userId;

	private Username $username;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId   $userId
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Username $username
	 *
	 * @return static
	 */
	public static function create(UserId $userId, Username $username): self
	{
		$event = self::occur($userId->toString(), [
			'username' => $username->value(),
		]);

		$event->userId = $userId;
		$event->username = $username;

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
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\Username
	 */
	public function username(): Username
	{
		return $this->username;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->userId = UserId::fromUuid($this->aggregateId()->id());
		$this->username = Username::fromValue($parameters['username']);
	}
}
