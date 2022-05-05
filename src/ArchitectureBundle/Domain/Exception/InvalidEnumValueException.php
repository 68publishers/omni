<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;

final class InvalidEnumValueException extends DomainException
{
	private string $valueObjectClassname;

	private string $value;

	/**
	 * @param string $message
	 * @param string $valueObjectClassname
	 * @param string $value
	 */
	private function __construct(string $message, string $valueObjectClassname, string $value)
	{
		parent::__construct($message);

		$this->valueObjectClassname = $valueObjectClassname;
		$this->value = $value;
	}

	/**
	 * @param string $valueObjectClassname
	 * @param string $value
	 * @param array  $values
	 *
	 * @return static
	 */
	public static function create(string $valueObjectClassname, string $value, array $values): self
	{
		return new self(sprintf(
			'Invalid value %s for enum %s. The value must be one of these: [%s].',
			$value,
			$valueObjectClassname,
			implode(', ', $values)
		), $valueObjectClassname, $value);
	}

	/**
	 * @return string
	 */
	public function valueObjectClassname(): string
	{
		return $this->valueObjectClassname;
	}

	/**
	 * @return string
	 */
	public function value(): string
	{
		return $this->value;
	}
}
