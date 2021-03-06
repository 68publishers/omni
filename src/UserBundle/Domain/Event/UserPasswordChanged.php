<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class UserPasswordChanged extends AbstractDomainEvent
{
	private UserId $userId;

	private HashedPassword $password;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId         $userId
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword $password
	 *
	 * @return static
	 */
	public static function create(UserId $userId, HashedPassword $password): self
	{
		$event = self::occur($userId->toString(), [
			'password' => $password->value(),
		]);

		$event->userId = $userId;
		$event->password = $password;

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
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword
	 */
	public function password(): HashedPassword
	{
		return $this->password;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->userId = UserId::fromUuid($this->aggregateId()->id());
		$this->password = HashedPassword::fromValue($parameters['password']);
	}
}
