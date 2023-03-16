<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\IIdentity as NetteIdentityInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\UserBundle\Application\Authentication\Identity as AuthIdentity;
use SixtyEightPublishers\UserBundle\Application\Exception\IdentityException;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Role as RoleValueObject;
use function array_map;
use function assert;

final class Identity extends AuthIdentity implements NetteIdentityInterface
{
    public static function of(AuthIdentity $identity): self
    {
        $newIdentity = new self();
        $newIdentity->id = $identity->id;
        $newIdentity->queryBus = $identity->queryBus;
        $newIdentity->data = $identity->data;
        $newIdentity->dataLoaded = $identity->dataLoaded;

        return $newIdentity;
    }

    /**
     * @return array<Role>
     * @throws IdentityException
     */
    public function getRoles(): array
    {
        return array_map(
            static function (ValueObjectInterface $role): Role {
                assert($role instanceof RoleValueObject);

                return new Role($role);
            },
            $this->getData()->roles->all(),
        );
    }
}
