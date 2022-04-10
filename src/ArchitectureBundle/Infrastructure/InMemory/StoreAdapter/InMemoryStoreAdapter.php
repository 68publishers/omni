<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\StoreAdapter;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter\AbstractNonTransactionalStoreAdapter;

final class InMemoryStoreAdapter extends AbstractNonTransactionalStoreAdapter
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
