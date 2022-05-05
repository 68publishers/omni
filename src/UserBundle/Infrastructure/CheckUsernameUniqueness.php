<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure;

use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByUsernameQuery;
use SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface;
use SixtyEightPublishers\UserBundle\Domain\Exception\UsernameUniquenessException;

final class CheckUsernameUniqueness implements CheckUsernameUniquenessInterface
{
	private QueryBusInterface $queryBus;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface $queryBus
	 */
	public function __construct(QueryBusInterface $queryBus)
	{
		$this->queryBus = $queryBus;
	}

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(UserId $userId, Username $username): void
	{
		$userView = $this->queryBus->dispatch(GetUserByUsernameQuery::create($username->value()));

		if (!$userView instanceof UserView) {
			return;
		}

		if (!$userView->id->equals($userId)) {
			throw UsernameUniquenessException::create($username->value());
		}
	}
}
