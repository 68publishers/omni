<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Platform;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Schema\Index;
use function array_combine;
use function array_filter;
use function explode;
use function implode;
use function in_array;
use function is_array;

final class PostgreSqlPlatform extends PostgreSQL100Platform
{
    private const INDEX_OPTION_CASE_INSENSITIVE = 'case-insensitive';
    private const INDEX_OPTION_INCLUDE = 'include';
    private const INDEX_OPTION_DESC = 'desc';

    /**
     * Adds options 'case-insensitive' and 'desc' for indexes
     *
     * {@inheritDoc}
     */
    public function getIndexFieldDeclarationListSQL(Index $index): string
    {
        $quotedColumns = $index->getQuotedColumns($this);

        $ciColumns = $index->hasOption(self::INDEX_OPTION_CASE_INSENSITIVE) ? $index->getOption(self::INDEX_OPTION_CASE_INSENSITIVE) : [];
        $descColumns = $index->hasOption(self::INDEX_OPTION_DESC) ? $index->getOption(self::INDEX_OPTION_DESC) : [];

        if (!is_array($ciColumns)) {
            $ciColumns = explode(',', (string) $ciColumns);
        }

        if (!is_array($descColumns)) {
            $descColumns = explode(',', (string) $descColumns);
        }

        $columns = array_combine($index->getUnquotedColumns(), $quotedColumns);

        foreach ($columns as $name => $quoted) {
            if (in_array($name, $ciColumns, true)) {
                $columns[$name] = 'lower(' . $quoted . ')';
            }

            if (in_array($name, $descColumns, true)) {
                $columns[$name] = $quoted . ' DESC';
            }
        }

        return implode(', ', $columns);
    }

    /**
     * Adds option 'include' for indexes
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
