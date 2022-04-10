<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;

final class TransportStamp implements NonSendableStampInterface
{
	private string $transportName;

	/**
	 * @param string $transportName
	 */
	public function __construct(string $transportName)
	{
		$this->transportName = $transportName;
	}

	/**
	 * @return string
	 */
	public function getTransportName(): string
	{
		return $this->transportName;
	}
}
