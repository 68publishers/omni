<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\Role as RoleInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Role as RoleValueObject;

final class Role implements RoleInterface
{
    public function __construct(
        private readonly RoleValueObject $role,
    ) {}

    public function getRoleId(): string
    {
        return $this->role->toNative();
    }

    public function equals(self $role): bool
    {
        return $this->role->equals(RoleValueObject::fromNative($role->getRoleId()));
    }
}
