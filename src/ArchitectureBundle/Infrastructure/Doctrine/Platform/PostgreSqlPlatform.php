<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Platform;

use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Table;
use function array_combine;
use function array_filter;
use function explode;
use function implode;
use function in_array;
use function is_array;
use function sprintf;

final class PostgreSqlPlatform extends PostgreSQL100Platform
{
    private const INDEX_OPTION_CASE_INSENSITIVE = 'case-insensitive';
    private const INDEX_OPTION_INCLUDE = 'include';
    private const INDEX_OPTION_DESC = 'desc';
    private const INDEX_OPTION_JSONB_PATH_OPS = 'jsonb_path_ops';
    private const INDEX_OPTION_GIN_TRGM_OPS = 'gin_trgm_ops';

    private const INDEX_FLAG_GIN = 'gin';

    /**
     * Adds options 'case-insensitive', 'desc' and 'jsonb_path_ops' for indexes
     *
     * {@inheritDoc}
     */
    public function getIndexFieldDeclarationListSQL(Index $index): string
    {
        $quotedColumns = $index->getQuotedColumns($this);

        $ciColumns = $index->hasOption(self::INDEX_OPTION_CASE_INSENSITIVE) ? $index->getOption(self::INDEX_OPTION_CASE_INSENSITIVE) : [];
        $descColumns = $index->hasOption(self::INDEX_OPTION_DESC) ? $index->getOption(self::INDEX_OPTION_DESC) : [];
        $jsonbPathsOpsColumns = $index->hasOption(self::INDEX_OPTION_JSONB_PATH_OPS) ? $index->getOption(self::INDEX_OPTION_JSONB_PATH_OPS) : [];
        $ginTrgmOpsColumns = $index->hasOption(self::INDEX_OPTION_GIN_TRGM_OPS) ? $index->getOption(self::INDEX_OPTION_GIN_TRGM_OPS) : [];

        if (!is_array($ciColumns)) {
            $ciColumns = explode(',', (string) $ciColumns);
        }

        if (!is_array($descColumns)) {
            $descColumns = explode(',', (string) $descColumns);
        }

        if (!is_array($jsonbPathsOpsColumns)) {
            $jsonbPathsOpsColumns = explode(',', (string) $jsonbPathsOpsColumns);
        }

        if (!is_array($ginTrgmOpsColumns)) {
            $ginTrgmOpsColumns = explode(',', (string) $ginTrgmOpsColumns);
        }

        $columns = array_combine($index->getUnquotedColumns(), $quotedColumns);

        foreach ($columns as $name => $quoted) {
            if (in_array($name, $ciColumns, true)) {
                $quoted = 'lower(' . $quoted . ')';
            }

            if (in_array($name, $descColumns, true)) {
                $quoted = $quoted . ' DESC';
            }

            if (in_array($name, $jsonbPathsOpsColumns, true)) {
                $quoted = $quoted . ' jsonb_path_ops';
            }

            if (in_array($name, $ginTrgmOpsColumns, true)) {
                $quoted = $quoted . ' gin_trgm_ops';
            }

            $columns[$name] = $quoted;
        }

        return implode(', ', $columns);
    }

    /**
     * Support for GIN indexes
     */
    public function getCreateIndexSQL(Index $index, $table): string
    {
        if ($index->hasFlag(self::INDEX_FLAG_GIN)) {
            $table = sprintf(
                '%s USING gin',
                $table instanceof Table ? $table->getQuotedName($this) : $table,
            );
        }

        return parent::getCreateIndexSQL($index, $table);
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
