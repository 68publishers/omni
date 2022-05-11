<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Message;

class Message implements MessageInterface
{
	protected array $parameters = [];

	private function __construct()
	{
	}

	/**
	 * @param array $parameters
	 *
	 * @return static
	 */
	public static function fromParameters(array $parameters): self
	{
		$message = new static();
		$message->parameters = $parameters;

		return $message;
	}

	/**
	 * {@inheritDoc}
	 */
	public function parameters(): array
	{
		return $this->parameters;
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasParam(string $name): bool
	{
		return array_key_exists($name, $this->parameters);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParam(string $name)
	{
		return $this->parameters[$name] ?? NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function withParam(string $name, $value): self
	{
		$parameters = $this->parameters();
		$parameters[$name] = $value;

		$message = new static();
		$message->parameters = $parameters;

		return $message;
	}
}
