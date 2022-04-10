<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use Nette\Security\IIdentity as NetteIdentityInterface;
use SixtyEightPublishers\UserBundle\Domain\Dto\Role as RoleDto;
use SixtyEightPublishers\UserBundle\Application\Authentication\Identity as AuthIdentity;

final class Identity extends AuthIdentity implements NetteIdentityInterface
{
	/**
	 * @param \SixtyEightPublishers\UserBundle\Bridge\Nette\Security\Identity $identity
	 *
	 * @return static
	 */
	public static function of(AuthIdentity $identity): self
	{
		$newIdentity = new self();
		$newIdentity->id = $identity->id;
		$newIdentity->queryBus = $identity->queryBus;
		$newIdentity->data = $identity->data;
		$newIdentity->dataLoaded = $identity->dataLoaded;

		return $newIdentity;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\Dto\UserId
	 */
	public function getId(): UserId
	{
		return $this->id();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\UserBundle\Application\Exception\IdentityException
	 */
	public function getRoles(): array
	{
		if (NULL === $this->data()) {
			return [];
		}

		return array_map(static fn (RoleDto $role): Role => Role::fromDto($role), $this->data()->roles->all());
	}
}
