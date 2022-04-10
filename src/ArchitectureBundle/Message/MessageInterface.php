<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Message;

interface MessageInterface
{
	/**
	 * @return array
	 */
	public function parameters(): array;

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function hasParam(string $name): bool;

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function getParam(string $name);

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function withParam(string $name, $value): self;
}
