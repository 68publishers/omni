<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\AbstractCommand;

final class UpdateUserCommand extends AbstractCommand
{
	/**
	 * @return static
	 */
	public static function create(string $userId): self
	{
		return self::fromParameters([
			'user_id' => $userId,
		]);
	}

	/**
	 * @return string
	 */
	public function userId(): string
	{
		return $this->getParam('user_id');
	}

	/**
	 * @return string|NULL
	 */
	public function emailAddress(): ?string
	{
		return $this->getParam('email_address');
	}

	/**
	 * @return string|NULL
	 */
	public function username(): ?string
	{
		return $this->getParam('username');
	}

	/**
	 * @return string|NULL
	 */
	public function password(): ?string
	{
		return $this->getParam('password');
	}

	/**
	 * @return array|NULL
	 */
	public function roles(): ?array
	{
		return $this->getParam('roles');
	}

	/**
	 * @return string|NULL
	 */
	public function firstname(): ?string
	{
		return $this->getParam('firstname');
	}

	/**
	 * @return string|NULL
	 */
	public function surname(): ?string
	{
		return $this->getParam('surname');
	}

	/**
	 * @param string $emailAddress
	 *
	 * @return $this
	 */
	public function withEmailAddress(string $emailAddress): self
	{
		return $this->withParam('email_address', $emailAddress);
	}

	/**
	 * @param string $username
	 *
	 * @return $this
	 */
	public function withUsername(string $username): self
	{
		return $this->withParam('username', $username);
	}

	/**
	 * @param string $password
	 *
	 * @return $this
	 */
	public function withPassword(string $password): self
	{
		return $this->withParam('password', $password);
	}

	/**
	 * @param array $roles
	 *
	 * @return $this
	 */
	public function withRoles(array $roles): self
	{
		return $this->withParam('roles', $roles);
	}

	/**
	 * @param string $firstname
	 *
	 * @return $this
	 */
	public function withFirstname(string $firstname): self
	{
		return $this->withParam('firstname', $firstname);
	}

	/**
	 * @param string $surname
	 *
	 * @return $this
	 */
	public function withSurname(string $surname): self
	{
		return $this->withParam('surname', $surname);
	}
}
