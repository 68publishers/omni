<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;

class CredentialsView extends AbstractView
{
	public UserId $id;

	public Username $username;

	public ?HashedPassword $password = NULL;

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id->toString(),
			'username' => $this->username->value(),
			'password' => NULL !== $this->password ? $this->password->value() : NULL,
		];
	}
}
