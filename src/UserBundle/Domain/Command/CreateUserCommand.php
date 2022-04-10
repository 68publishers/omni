<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\AbstractCommand;

final class CreateUserCommand extends AbstractCommand
{
	/**
	 * @param string      $username
	 * @param string|NULL $password
	 * @param string      $emailAddress
	 * @param string      $firstname
	 * @param string      $surname
	 * @param array       $roles
	 * @param string|NULL $userId
	 *
	 * @return static
	 */
	public static function create(string $username, ?string $password, string $emailAddress, string $firstname, string $surname, array $roles = [], ?string $userId = NULL): self
	{
		return self::fromParameters([
			'username' => $username,
			'password' => $password,
			'email_address' => $emailAddress,
			'firstname' => $firstname,
			'surname' => $surname,
			'roles' => $roles,
			'user_id' => $userId,
		]);
	}

	/**
	 * @return string|NULL
	 */
	public function userId(): ?string
	{
		return $this->getParam('user_id');
	}

	/**
	 * @return string
	 */
	public function username(): string
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
	 * @return string
	 */
	public function emailAddress(): string
	{
		return $this->getParam('email_address');
	}

	/**
	 * @return string
	 */
	public function firstname(): string
	{
		return $this->getParam('firstname');
	}

	/**
	 * @return string
	 */
	public function surname(): string
	{
		return $this->getParam('surname');
	}

	/**
	 * @return string[]
	 */
	public function roles(): array
	{
		return $this->getParam('roles');
	}
}
