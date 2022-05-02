<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use DateTimeImmutable;
use DateTimeInterface;
use SixtyEightPublishers\UserBundle\Domain\Dto\Name;
use SixtyEightPublishers\UserBundle\Domain\Dto\Roles;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\Domain\Dto\Username;
use SixtyEightPublishers\UserBundle\Domain\Dto\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface;

class UserView extends AbstractView
{
	public UserId $id;

	public DateTimeImmutable $createdAt;

	public Username $username;

	public HashedPassword $password;

	public EmailAddressInterface $emailAddress;

	public Name $name;

	public Roles $roles;

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return [
			# password is omitted
			'id' => $this->id->toString(),
			'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
			'username' => $this->username->value(),
			'emailAddress' => $this->emailAddress->value(),
			'name' => [
				'name' => $this->name->name(),
				'firstname' => $this->name->firstname(),
				'surname' => $this->name->surname(),
			],
			'roles' => $this->roles->toArray(),
		];
	}
}
