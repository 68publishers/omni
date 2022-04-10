<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;

final class IdentityDecorator extends Identity
{
	/**
	 * @return $this
	 */
	public static function newInstance(): self
	{
		return new self();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Application\Authentication\Identity $identity
	 *
	 * @return \SixtyEightPublishers\UserBundle\Application\Authentication\Identity
	 */
	public function sleepIdentity(Identity $identity): Identity
	{
		return $identity->sleep();
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Application\Authentication\Identity $identity
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface       $queryBus
	 *
	 * @return \SixtyEightPublishers\UserBundle\Application\Authentication\Identity|NULL
	 */
	public function wakeupIdentity(Identity $identity, QueryBusInterface $queryBus): Identity
	{
		return $identity->wakeup($queryBus);
	}
}
