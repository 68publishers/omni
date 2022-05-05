<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class UserNameChanged extends AbstractDomainEvent
{
	private UserId $userId;

	private Name $name;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId $userId
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Name   $name
	 *
	 * @return static
	 */
	public static function create(UserId $userId, Name $name): self
	{
		$event = self::occur($userId->toString(), [
			'firstname' => $name->firstname(),
			'surname' => $name->surname(),
		]);

		$event->userId = $userId;
		$event->name = $name;

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
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\Name
	 */
	public function name(): Name
	{
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->userId = UserId::fromUuid($this->aggregateId()->id());
		$this->name = Name::fromValues($parameters['firstname'], $parameters['surname']);
	}
}
