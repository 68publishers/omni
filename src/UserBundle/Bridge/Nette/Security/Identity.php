<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\IIdentity as NetteIdentityInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Role as RoleValueObject;
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

	public function getId(): string
	{
		return $this->id()->toString();
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

		return array_map(static fn (RoleValueObject $role): Role => Role::fromValueObject($role), $this->data()->roles->all());
	}
}
