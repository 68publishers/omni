<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

final class UserCreated extends AbstractDomainEvent
{
	private UserId $userId;

	private Username $username;

	private ?HashedPassword $password = NULL;

	private EmailAddressInterface $emailAddress;

	private Name $name;

	private Roles $roles;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId                        $userId
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Username                      $username
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword|NULL           $password
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Name                          $name
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles                         $roles
	 *
	 * @return static
	 */
	public static function create(UserId $userId, Username $username, ?HashedPassword $password, EmailAddressInterface $emailAddress, Name $name, Roles $roles): self
	{
		$event = self::occur($userId->toString(), [
			'username' => $username->value(),
			'password' => NULL !== $password ? $password->value() : NULL,
			'email_address' => $emailAddress->value(),
			'firstname' => $name->firstname(),
			'surname' => $name->surname(),
			'roles' => $roles->toArray(),
		]);

		$event->userId = $userId;
		$event->username = $username;
		$event->password = $password;
		$event->emailAddress = $emailAddress;
		$event->name = $name;
		$event->roles = $roles;

		return $event;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\Username
	 */
	public function username(): Username
	{
		return $this->username;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword
	 */
	public function password(): ?HashedPassword
	{
		return $this->password;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface
	 */
	public function emailAddress(): EmailAddressInterface
	{
		return $this->emailAddress;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\Name
	 */
	public function name(): Name
	{
		return $this->name;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles
	 */
	public function roles(): Roles
	{
		return $this->roles;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->userId = UserId::fromUuid($this->aggregateId()->id());
		$this->username = Username::fromValue($parameters['username']);
		$this->password = isset($parameters['password']) ? HashedPassword::fromValue($parameters['password']) : NULL;
		$this->emailAddress = EmailAddress::fromValue($parameters['email_address']);
		$this->name = Name::fromValues($parameters['firstname'], $parameters['surname']);
		$this->roles = Roles::reconstitute($parameters['roles']);
	}
}
