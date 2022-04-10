<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

interface QueryBusInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface $message
	 *
	 * @return mixed
	 */
	public function dispatch(QueryInterface $message);
}
