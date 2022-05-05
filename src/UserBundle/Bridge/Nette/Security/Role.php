<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\Role as RoleInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Role as RoleValueObject;

final class Role implements RoleInterface
{
	private RoleValueObject $role;

	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Role $role
	 *
	 * @return $this
	 */
	public static function fromValueObject(RoleValueObject $role): self
	{
		$self = new self();
		$self->role = $role;

		return $self;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getRoleId(): string
	{
		return $this->role->value();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Bridge\Nette\Security\Role $role
	 *
	 * @return string
	 */
	public function equals(self $role): string
	{
		return $this->role->equals(RoleValueObject::fromValue($role->getRoleId()));
	}
}
