<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Platform;

use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Platforms\PostgreSQL100Platform;

final class PostgreSqlPlatform extends PostgreSQL100Platform
{
	private const INDEX_OPTION_CASE_INSENSITIVE = 'case-insensitive';
	private const INDEX_OPTION_INCLUDE = 'include';

	/**
	 * Adds option 'case-insensitive' for indexes
	 *
	 * {@inheritDoc}
	 */
	public function getIndexFieldDeclarationListSQL(Index $index): string
	{
		$quotedColumns = $index->getQuotedColumns($this);

		if (!$index->hasOption(self::INDEX_OPTION_CASE_INSENSITIVE)) {
			return implode(', ', $quotedColumns);
		}

		$ciColumns = $index->getOption(self::INDEX_OPTION_CASE_INSENSITIVE);

		if (!is_array($ciColumns)) {
			$ciColumns = explode(',', (string) $ciColumns);
		}

		$columns = array_combine($index->getUnquotedColumns(), $quotedColumns);

		foreach ($columns as $name => $quoted) {
			if (in_array($name, $ciColumns, TRUE)) {
				$columns[$name] = 'lower(' . $quoted . ')';
			}
		}

		return implode(', ', $columns);
	}

	/**
	 * Adds option 'include' for indexes
	 *
	 * @param \Doctrine\DBAL\Schema\Index $index
	 *
	 * @return string
	 */
	protected function getPartialIndexSQL(Index $index): string
	{
		$parts = [];

		if ($index->hasOption(self::INDEX_OPTION_INCLUDE)) {
			$includeColumns = $index->getOption(self::INDEX_OPTION_INCLUDE);

			if (!is_array($includeColumns)) {
				$includeColumns = explode(',', (string) $includeColumns);
			}

			$parts[] = 'INCLUDE (' . implode(', ', $includeColumns) . ')';
		}

		$parts[] = parent::getPartialIndexSQL($index);
		$parts = array_filter($parts, static fn ($part): bool => !empty($part));

		return !empty($parts) ? ' ' . implode(' ', $parts) : '';
	}
}
