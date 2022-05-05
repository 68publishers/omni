<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure;

use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByEmailAddressQuery;
use SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;
use SixtyEightPublishers\UserBundle\Domain\Exception\EmailAddressUniquenessException;

final class CheckEmailAddressUniqueness implements CheckEmailAddressUniquenessInterface
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
	public function __invoke(UserId $userId, EmailAddressInterface $emailAddress): void
	{
		$userView = $this->queryBus->dispatch(GetUserByEmailAddressQuery::create($emailAddress->value()));

		if (!$userView instanceof UserView) {
			return;
		}

		if (!$userView->id->equals($userId)) {
			throw EmailAddressUniquenessException::create($emailAddress->value());
		}
	}
}
