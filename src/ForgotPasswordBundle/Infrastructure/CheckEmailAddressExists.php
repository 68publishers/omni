<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByEmailAddressQuery;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\CheckEmailAddressExistsInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\EmailAddressNotFoundException;

final class CheckEmailAddressExists implements CheckEmailAddressExistsInterface
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
	public function __invoke(EmailAddressInterface $emailAddress): void
	{
		if (NULL === $this->queryBus->dispatch(GetUserByEmailAddressQuery::create($emailAddress->value()))) {
			throw EmailAddressNotFoundException::create($emailAddress->value());
		}
	}
}
