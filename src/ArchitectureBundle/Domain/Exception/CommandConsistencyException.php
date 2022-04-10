<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;

final class CommandConsistencyException extends DomainException
{
	private array $messages;

	/**
	 * @param array $messages
	 */
	public function __construct(array $messages)
	{
		parent::__construct(sprintf(
			'The following consistency errors has been produced: [%s]',
			implode(', ', $messages)
		));

		$this->messages = $messages;
	}

	/**
	 * @return static
	 */
	public static function empty(): self
	{
		return new self([]);
	}

	/**
	 * @param string $message
	 *
	 * @return static
	 */
	public static function fromMessage(string $message): self
	{
		return new self([$message]);
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Exception\CommandConsistencyException $exception
	 *
	 * @return $this
	 */
	public function withException(self $exception): self
	{
		$messages = array_merge($this->getMessages(), $exception->getMessages());

		return new self($messages);
	}

	/**
	 * @return string[]
	 */
	public function getMessages(): array
	{
		return $this->messages;
	}
}
