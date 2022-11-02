<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionStore;

use Exception;
use Throwable;

final class ProjectionStoreException extends Exception
{
	private bool $retryable;

	private function __construct(string $message, bool $retryable, ?Throwable $previous = NULL)
	{
		parent::__construct($message, 0, $previous);

		$this->retryable = $retryable;
	}

	public static function unableToFindLastPositions(string $projectionClassname, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to find last positions for the projection %s. %s',
			$projectionClassname,
			$previous ? $previous->getMessage() : ''
		), $retryable, $previous);
	}

	public static function unableToUpdateLastPosition(string $projectionClassname, string $aggregateClassname, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to update last position for the projection %s and aggregate type %s. %s',
			$projectionClassname,
			$aggregateClassname,
			$previous ? $previous->getMessage() : ''
		), $retryable, $previous);
	}

	public static function unableToResetProjection(string $projectionClassname, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to reset the projection %s. %s',
			$projectionClassname,
			$previous ? $previous->getMessage() : ''
		), $retryable, $previous);
	}

	public static function unableToResetProjectionModel(string $projectionClassname, string $projectionModelClassname, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to reset the projection model %s for the projection %s. %s',
			$projectionModelClassname,
			$projectionClassname,
			$previous ? $previous->getMessage() : ''
		), $retryable, $previous);
	}

	public static function of(Throwable $error, bool $retryable): self
	{
		return new self(sprintf(
			'ProjectionStore error: %s',
			$error->getMessage()
		), $retryable, $error);
	}

	public function retryable(): bool
	{
		return $this->retryable;
	}
}
