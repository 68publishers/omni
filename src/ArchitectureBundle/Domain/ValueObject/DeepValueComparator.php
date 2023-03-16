<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use function array_is_list;
use function array_key_exists;
use function count;
use function is_array;
use function sort;

final class DeepValueComparator
{
    private function __construct() {}

    public static function compare(mixed $left, mixed $right): bool
    {
        if (is_array($left) && is_array($right)) {
            if (count($left) !== count($right)) {
                return false;
            }

            $isLeftList = !$left || array_is_list($left);
            $isLRightList = !$right || array_is_list($right);

            if (($isLeftList && !$isLRightList) || (!$isLeftList && $isLRightList)) {
                return false;
            }

            if ($isLeftList) {
                sort($left);
                sort($right);
            }

            foreach ($left as $k => $v) {
                if (!array_key_exists($k, $right) || !self::compare($v, $right[$k])) {
                    return false;
                }
            }

            return true;
        }

        return $left === $right;
    }
}
