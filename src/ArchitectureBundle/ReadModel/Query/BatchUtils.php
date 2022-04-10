<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

final class BatchUtils
{
	private function __construct()
	{
	}

	/**
	 * @param int $total
	 * @param int $batch
	 *
	 * @return array [[(int) limit, (int) offset], ...]
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
	 * @param int      $total
	 * @param int      $batch
	 * @param callable $callback function(int $limit, int $offset) {}
	 *
	 * @return void
	 */
	public static function apply(int $total, int $batch, callable $callback): void
	{
		foreach (self::from($total, $batch) as $offsets) {
			$callback(...$offsets);
		}
	}
}
