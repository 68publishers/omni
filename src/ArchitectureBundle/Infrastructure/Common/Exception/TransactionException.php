<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception;

use RuntimeException;

final class TransactionException extends RuntimeException
{
	/**
	 * @param string $message
	 */
	private function __construct(string $message)
	{
		parent::__construct($message);
	}

	/**
	 * @param string|NULL $reason
	 *
	 * @return $this
	 */
	public static function unableToBeginTransaction(?string $reason = NULL): self
	{
		return new self(sprintf(
			'Unable to begin a transaction.%s',
			!empty($reason) ? ' ' . $reason : ''
		));
	}

	/**
	 * @param string|NULL $reason
	 *
	 * @return $this
	 */
	public static function unableToCommitTransaction(?string $reason = NULL): self
	{
		return new self(sprintf(
			'Unable to commit a transaction.%s',
			!empty($reason) ? ' ' . $reason : ''
		));
	}

	/**
	 * @param string|NULL $reason
	 *
	 * @return $this
	 */
	public static function unableToRollbackTransaction(?string $reason = NULL): self
	{
		return new self(sprintf(
			'Unable to rollback a transaction.%s',
			!empty($reason) ? ' ' . $reason : ''
		));
	}
}
