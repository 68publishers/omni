<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\PersistenceAdapter;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\AbstractNonTransactionalPersistenceAdapter;

final class InMemoryPersistenceAdapter extends AbstractNonTransactionalPersistenceAdapter
{
	/**
	 * {@inheritDoc}
	 */
	public function pingConnection(): void
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public function closeConnection(): void
	{
	}
}
