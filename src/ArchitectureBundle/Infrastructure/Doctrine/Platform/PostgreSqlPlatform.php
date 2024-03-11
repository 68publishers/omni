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
use function str_starts_with;
use function strlen;
use function substr;

final class PostgreSqlPlatform extends PostgreSQL100Platform
{
    private const IndexOptCustomDeclaration = 'customDeclaration:';
    private const IndexOptInclude = 'include';
    private const IndexFlagGin = 'gin';

    /**
     * The following options are @deprecated and can be replaced with `customDeclaration:`
     */
    private const IndexOptCaseInsensitive = 'case-insensitive';
    private const IndexOptDesc = 'desc';
    private const IndexOptJsonbPathOps = 'jsonb_path_ops';
    private const IndexOptGinTrgmOps = 'gin_trgm_ops';

    /**
     * Support for custom custom index columns declarations
     *
     * {@inheritDoc}
     */
    public function getIndexFieldDeclarationListSQL(Index $index): string
    {
        $quotedColumns = $index->getQuotedColumns($this);

        $customDeclarations = [];

        foreach ($index->getOptions() as $key => $value) {
            if (str_starts_with($key, self::IndexOptCustomDeclaration)) {
                $columnName = substr(
                    string: $key,
                    offset: strlen(self::IndexOptCustomDeclaration),
                );

                $customDeclarations[$columnName] = $value;
            }
        }

        $ciColumns = $index->hasOption(self::IndexOptCaseInsensitive) ? $index->getOption(self::IndexOptCaseInsensitive) : [];
        $descColumns = $index->hasOption(self::IndexOptDesc) ? $index->getOption(self::IndexOptDesc) : [];
        $jsonbPathsOpsColumns = $index->hasOption(self::IndexOptJsonbPathOps) ? $index->getOption(self::IndexOptJsonbPathOps) : [];
        $ginTrgmOpsColumns = $index->hasOption(self::IndexOptGinTrgmOps) ? $index->getOption(self::IndexOptGinTrgmOps) : [];

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
            # custom declaration has top priority
            if (isset($customDeclarations[$name])) {
                $columns[$name] = sprintf(
                    $customDeclarations[$name],
                    $quoted,
                );

                continue;
            }

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
        if ($index->hasFlag(self::IndexFlagGin)) {
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

        if ($index->hasOption(self::IndexOptInclude)) {
            $includeColumns = $index->getOption(self::IndexOptInclude);

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
