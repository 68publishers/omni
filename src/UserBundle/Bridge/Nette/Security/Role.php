<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\Role as RoleInterface;
use SixtyEightPublishers\UserBundle\Domain\Dto\Role as RoleDto;

final class Role implements RoleInterface
{
	private RoleDto $role;

	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\Role $role
	 *
	 * @return $this
	 */
	public static function fromDto(RoleDto $role): self
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
		return $this->role->equals(RoleDto::fromValue($role->getRoleId()));
	}
}
