<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger;

final class MessageBusConfiguration
{
	public string $busName;

	/** @var string|array */
	public $configuration;

	public array $messageHandlerTypes;

	private function __construct(string $busName, $configuration, array $messageHandlerTypes)
	{
		$this->busName = $busName;
		$this->configuration = $configuration;
		$this->messageHandlerTypes = $messageHandlerTypes;
	}

	public static function fromArray(string $busName, array $configuration, array $messageHandlerTypes = []): self
	{
		return new self($busName, $configuration, $messageHandlerTypes);
	}

	public static function fromFile(string $busName, string $filename, array $messageHandlerTypes = []): self
	{
		return new self($busName, $filename, $messageHandlerTypes);
	}
}
