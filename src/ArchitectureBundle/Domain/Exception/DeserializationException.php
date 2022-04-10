<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;

final class DeserializationException extends DomainException
{
	/**
	 * @param string $message
	 */
	private function __construct(string $message)
	{
		parent::__construct($message);
	}

	/**
	 * @param string      $key
	 * @param string      $serializedObjectClassname
	 * @param string|NULL $contextMessage
	 *
	 * @return static
	 */
	public static function missingKey(string $key, string $serializedObjectClassname, ?string $contextMessage = NULL): self
	{
		return new self(sprintf(
			'Can\'t deserialize object of type %s because of missing required key %s.%s',
			$serializedObjectClassname,
			$key,
			empty($contextMessage) ? '' : ' ' . $contextMessage
		));
	}

	/**
	 * @param string $serializedObjectClassname
	 * @param string $what
	 * @param string $expectedType
	 * @param string $passedType
	 *
	 * @return static
	 */
	public static function invalidType(string $serializedObjectClassname, string $what, string $expectedType, string $passedType): self
	{
		return new self(sprintf(
			'Can\'t deserialize object of type %s. %s must be of type %s but %s passed.',
			$serializedObjectClassname,
			$what,
			$expectedType,
			$passedType
		));
	}
}
