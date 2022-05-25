<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use DateTimeImmutable;
use DateTimeInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

class UserView extends AbstractView
{
	public UserId $id;

	public DateTimeImmutable $createdAt;

	public ?DateTimeImmutable $deletedAt = NULL;

	public Username $username;

	public ?HashedPassword $password = NULL;

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
			'deletedAt' => NULL !== $this->deletedAt ? $this->deletedAt->format(DateTimeInterface::ATOM) : NULL,
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
