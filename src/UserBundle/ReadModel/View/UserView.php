<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use DateTimeImmutable;
use SixtyEightPublishers\UserBundle\Domain\Dto\Name;
use SixtyEightPublishers\UserBundle\Domain\Dto\Roles;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\Domain\Dto\Username;
use SixtyEightPublishers\UserBundle\Domain\Dto\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface;

/**
 * @property-read UserId $id
 * @property-read DateTimeImmutable $createdAt
 * @property-read Username $username
 * @property-read HashedPassword $password
 * @property-read EmailAddressInterface $emailAddress
 * @property-read Name $name
 * @property-read Roles $roles
 */
class UserView extends AbstractView
{
}
