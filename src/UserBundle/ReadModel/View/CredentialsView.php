<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\Domain\Dto\Username;
use SixtyEightPublishers\UserBundle\Domain\Dto\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;

class CredentialsView extends AbstractView
{
	public UserId $id;

	public Username $username;

	public HashedPassword $password;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId         $id
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\Username       $username
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\HashedPassword $password
	 *
	 * @return static
	 */
	public static function fromCredentials(UserId $id, Username $username, HashedPassword $password): self
	{
		return self::fromArray([
			'id' => $id,
			'username' => $username,
			'password' => $password,
		]);
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id->toString(),
			'username' => $this->username->value(),
			'password' => $this->password->value(),
		];
	}
}
