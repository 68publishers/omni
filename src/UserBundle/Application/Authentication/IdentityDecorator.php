<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;

final class IdentityDecorator extends Identity
{
    public static function newInstance(): self
    {
        return new self();
    }

    public function sleepIdentity(Identity $identity): Identity
    {
        return $identity->sleep();
    }

    public function wakeupIdentity(Identity $identity, QueryBusInterface $queryBus): Identity
    {
        return $identity->wakeup($queryBus);
    }
}
