<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;

final class InvalidIdentityValueException extends DomainException
{
	private string $identifier;

	private string $identityValueObjectClassname;

	/**
	 * @param string $message
	 * @param string $identifier
	 * @param string $identityValueObjectClassname
	 */
	private function __construct(string $message, string $identifier, string $identityValueObjectClassname)
	{
		parent::__construct($message);

		$this->identifier = $identifier;
		$this->identityValueObjectClassname = $identityValueObjectClassname;
	}

	/**
	 * @param string $identifier
	 * @param string $identityValueObjectClassname
	 *
	 * @return static
	 */
	public static function create(string $identifier, string $identityValueObjectClassname): self
	{
		return new self(sprintf(
			'Invalid identifier value %s for identity of type %s.',
			$identifier,
			$identityValueObjectClassname
		), $identifier, $identityValueObjectClassname);
	}


	/**
	 * @return string
	 */
	public function identifier(): string
	{
		return $this->identifier;
	}

	/**
	 * @return string
	 */
	public function identityValueObjectClassname(): string
	{
		return $this->identityValueObjectClassname;
	}
}
