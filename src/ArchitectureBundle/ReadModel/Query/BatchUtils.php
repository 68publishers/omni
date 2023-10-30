<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

use Closure;
use function array_fill;
use function array_map;
use function ceil;

final class BatchUtils
{
    private function __construct() {}

    /**
     * @return array<array{0: int, 1: int}> [[(int) limit, (int) offset], ...]
     */
    public static function from(int $total, int $batch): array
    {
        if (0 >= $total) {
            return [];
        }

        $iterator = 0;
        $length = (int) ceil($total / $batch);

        return array_map(static function ($arr) use (&$iterator, &$total, $length) {
            $arr[] = ($iterator * $arr[0]);
            $iterator++;
            if ($iterator === $length) { # last
                $arr[0] = $total;
            }
            $total -= $arr[0];

            return $arr;
        }, array_fill(0, $length, [$batch]));
    }

    /**
     * @param Closure(int $limit, int $offset): void $callback
     */
    public static function apply(int $total, int $batch, Closure $callback): void
    {
        foreach (self::from($total, $batch) as $offsets) {
            $callback($offsets[0], $offsets[1]);
        }
    }
}
