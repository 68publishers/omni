<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Aggregate;

use DateTimeImmutable;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Role;
use SixtyEightPublishers\UserBundle\Domain\Event\UserCreated;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Password;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\UserBundle\Domain\Event\UserNameChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserRolesChanged;
use SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\Event\UserPasswordChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserUsernameChanged;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\UserBundle\Domain\Event\UserEmailAddressChanged;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface;
use SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValidEmailAddress;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\DeletableAggregateRootTrait;

class User implements AggregateRootInterface
{
	use DeletableAggregateRootTrait;

	protected UserId $id;

	protected DateTimeImmutable $createdAt;

	protected Username $username;

	protected ?HashedPassword $password;

	protected EmailAddressInterface $emailAddress;

	protected Name $name;

	protected Roles $roles;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand            $command
	 * @param \SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface       $algorithm
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface     $checkUsernameUniqueness
	 *
	 * @return static
	 */
	public static function create(CreateUserCommand $command, PasswordHashAlgorithmInterface $algorithm, CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness, CheckUsernameUniquenessInterface $checkUsernameUniqueness): self
	{
		$user = new self();

		$userId = NULL !== $command->userId() ? UserId::fromString($command->userId()) : UserId::new();
		$password = NULL !== $command->password() ? Password::fromValue($command->password())->createHashedPassword($algorithm) : NULL;
		$username = Username::fromValue($command->username());
		$emailAddress = ValidEmailAddress::fromValue($command->emailAddress());
		$name = Name::fromValues($command->firstname(), $command->surname());
		$roles = Roles::reconstitute($command->roles());

		$checkEmailAddressUniqueness($userId, $emailAddress);
		$checkUsernameUniqueness($userId, $username);

		$user->recordThat(UserCreated::create($userId, $username, $password, $emailAddress, $name, $roles));

		return $user;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand            $command
	 * @param \SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface       $algorithm
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface     $checkUsernameUniqueness
	 *
	 * @return void
	 */
	public function update(UpdateUserCommand $command, PasswordHashAlgorithmInterface $algorithm, CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness, CheckUsernameUniquenessInterface $checkUsernameUniqueness): void
	{
		if (NULL !== $command->emailAddress()) {
			$this->changeEmailAddress(EmailAddress::fromValue($command->emailAddress()), $checkEmailAddressUniqueness);
		}

		if (NULL !== $command->username()) {
			$this->changeUsername(Username::fromValue($command->username()), $checkUsernameUniqueness);
		}

		if (NULL !== $command->password()) {
			$this->changePassword(Password::fromValue($command->password())->createHashedPassword($algorithm));
		}

		if (NULL !== $command->roles()) {
			$this->changeRoles(Roles::reconstitute($command->roles()));
		}

		if (NULL !== $command->firstname() || NULL !== $command->surname()) {
			$this->changeName(Name::fromValues($command->firstname() ?? $this->name->firstname(), $command->surname() ?? $this->name->surname()));
		}
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId
	 */
	public function aggregateId(): AggregateId
	{
		return AggregateId::fromUuid($this->id->id());
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Username             $username
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface $checkUsernameUniqueness
	 *
	 * @return void
	 */
	public function changeUsername(Username $username, CheckUsernameUniquenessInterface $checkUsernameUniqueness): void
	{
		if (!$this->username->equals($username)) {
			$checkUsernameUniqueness($this->id, $username);
			$this->recordThat(UserUsernameChanged::create($this->id, $username));
		}
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword $password
	 *
	 * @return void
	 */
	public function changePassword(HashedPassword $password): void
	{
		$this->recordThat(UserPasswordChanged::create($this->id, $password));
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface      $checkEmailAddressUniqueness
	 *
	 * @return void
	 */
	public function changeEmailAddress(EmailAddressInterface $emailAddress, CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness): void
	{
		if (!$this->emailAddress->equals($emailAddress)) {
			$checkEmailAddressUniqueness($this->id, $emailAddress);
			$this->recordThat(UserEmailAddressChanged::create($this->id, ValidEmailAddress::fromInstance($emailAddress)));
		}
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Name $name
	 *
	 * @return void
	 */
	public function changeName(Name $name): void
	{
		if (!$this->name->equals($name)) {
			$this->recordThat(UserNameChanged::create($this->id, $name));
		}
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Role $role
	 *
	 * @return void
	 */
	public function addRole(Role $role): void
	{
		if (!$this->roles->has($role)) {
			$this->recordThat(UserRolesChanged::create($this->id, $this->roles->with($role)));
		}
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Role $role
	 *
	 * @return void
	 */
	public function removeRole(Role $role): void
	{
		if ($this->roles->has($role)) {
			$this->recordThat(UserRolesChanged::create($this->id, $this->roles->without($role)));
		}
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles $roles
	 *
	 * @return void
	 */
	public function changeRoles(Roles $roles): void
	{
		if (!$this->roles->equals($roles)) {
			$this->recordThat(UserRolesChanged::create($this->id, $roles));
		}
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Event\UserCreated $event
	 *
	 * @return void
	 */
	protected function whenUserCreated(UserCreated $event): void
	{
		$this->id = $event->userId();
		$this->createdAt = $event->createdAt();
		$this->username = $event->username();
		$this->password = $event->password();
		$this->emailAddress = $event->emailAddress();
		$this->name = $event->name();
		$this->roles = $event->roles();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Event\UserUsernameChanged $event
	 *
	 * @return void
	 */
	protected function whenUserUsernameChanged(UserUsernameChanged $event): void
	{
		$this->username = $event->username();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Event\UserPasswordChanged $event
	 *
	 * @return void
	 */
	protected function whenUserPasswordChanged(UserPasswordChanged $event): void
	{
		$this->password = $event->password();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Event\UserEmailAddressChanged $event
	 *
	 * @return void
	 */
	protected function whenUserEmailAddressChanged(UserEmailAddressChanged $event): void
	{
		$this->emailAddress = $event->emailAddress();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Event\UserNameChanged $event
	 *
	 * @return void
	 */
	protected function whenUserNameChanged(UserNameChanged $event): void
	{
		$this->name = $event->name();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Event\UserRolesChanged $event
	 *
	 * @return void
	 */
	protected function whenUserRolesChanged(UserRolesChanged $event): void
	{
		$this->roles = $event->roles();
	}
}
