<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\Exception\PasswordException;

final class PasswordHashAlgorithm implements PasswordHashAlgorithmInterface
{
	/** @var string|int */
	private $algo;

	private array $options;

	/**
	 * @param string|int $algo
	 * @param array      $options
	 */
	public function __construct($algo = PASSWORD_DEFAULT, array $options = [])
	{
		$this->algo = $algo;
		$this->options = $options;
	}

	/**
	 * {@inheritDoc}
	 */
	public function hash(string $rawPassword): string
	{
		if ('' === $rawPassword) {
			throw PasswordException::emptyPassword();
		}

		$hash = @password_hash($rawPassword, $this->algo, $this->options);

		if (!$hash) {
			throw PasswordException::cantHashPassword(error_get_last()['message']);
		}

		return $hash;
	}
}
