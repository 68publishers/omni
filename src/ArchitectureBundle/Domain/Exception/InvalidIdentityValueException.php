<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;

final class InvalidIdentityValueException extends DomainException
{
	private string $identifier;

	private string $identityDtoClassname;

	/**
	 * @param string $message
	 * @param string $identifier
	 * @param string $identityDtoClassname
	 */
	private function __construct(string $message, string $identifier, string $identityDtoClassname)
	{
		parent::__construct($message);

		$this->identifier = $identifier;
		$this->identityDtoClassname = $identityDtoClassname;
	}

	/**
	 * @param string $identifier
	 * @param string $identityDtoClassname
	 *
	 * @return static
	 */
	public static function create(string $identifier, string $identityDtoClassname): self
	{
		return new self(sprintf(
			'Invalid identifier value %s for identity of type %s.',
			$identifier,
			$identityDtoClassname
		), $identifier, $identityDtoClassname);
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
	public function identityDtoClassname(): string
	{
		return $this->identityDtoClassname;
	}
}
