<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByIdQuery;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\CommandConsistencyException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand;

final class UserExistsGuard implements PasswordRequestCommandConsistencyGuardInterface
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
	public function __invoke(CommandInterface $command): void
	{
		if ($command instanceof RequestPasswordChangeCommand && NULL === $this->queryBus->dispatch(GetUserByIdQuery::create($command->userId()))) {
			throw CommandConsistencyException::fromMessage('The user for whom the password change was requested was not found.');
		}
	}
}
