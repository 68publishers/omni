<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\Dto\Roles;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class UserRolesChanged extends AbstractDomainEvent
{
	private UserId $userId;

	private Roles $roles;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId $userId
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\Roles  $roles
	 *
	 * @return static
	 */
	public static function create(UserId $userId, Roles $roles): self
	{
		$event = self::occur($userId->toString(), [
			'roles' => $roles->toArray(),
		]);

		$event->userId = $userId;
		$event->roles = $roles;

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
	 * @return \SixtyEightPublishers\UserBundle\Domain\Dto\Roles
	 */
	public function roles(): Roles
	{
		return $this->roles;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->userId = UserId::fromUuid($this->aggregateId()->id());
		$this->roles = Roles::reconstitute($parameters['roles']);
	}
}
