<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

abstract class AbstractArrayValueObject implements ArrayValueObjectInterface
{
	private array $values;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromArray(array $array): self
	{
		$valueObject = new static();
		$valueObject->values = $array;

		return $valueObject;
	}

	/**
	 * {@inheritDoc}
	 */
	public function values(): array
	{
		return $this->values;
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(string $name)
	{
		return $this->values[$name] ?? NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function has(string $name): bool
	{
		return isset($this->values[$name]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function equals(ComparableValueObjectInterface $valueObject): bool
	{
		if (!$valueObject instanceof static) {
			return FALSE;
		}

		return $this->doCompareValues($this->values(), $valueObject->values());
	}

	/**
	 * @param array $left
	 * @param array $right
	 *
	 * @return bool
	 */
	protected function doCompareValues(array $left, array $right): bool
	{
		return $this->compareValues($left, $right);
	}

	/**
	 * @param mixed $left
	 * @param mixed $right
	 *
	 * @return bool
	 */
	private function compareValues($left, $right): bool
	{
		if (is_array($left) && is_array($right)) {
			if (count($left) !== count($right)) {
				return FALSE;
			}

			$isLeftList = !$left || array_keys($left) === range(0, count($left) - 1);
			$isLRightList = !$right || array_keys($right) === range(0, count($right) - 1);

			if (($isLeftList && !$isLRightList) || (!$isLeftList && $isLRightList)) {
				return FALSE;
			}

			if ($isLeftList && $isLRightList) {
				sort($left);
				sort($right);
			}

			foreach ($left as $k => $v) {
				if (!array_key_exists($k, $right) || !$this->compareValues($v, $right[$k])) {
					return FALSE;
				}
			}

			return TRUE;
		}

		return $left === $right;
	}
}
