<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\AbstractCommand;

final class RequestPasswordChangeCommand extends AbstractCommand
{
	/**
	 * @param string      $userId
	 * @param string      $ipAddress
	 * @param string      $userAgent
	 * @param string|NULL $passwordRequestId
	 *
	 * @return static
	 */
	public static function create(string $userId, string $ipAddress, string $userAgent, ?string $passwordRequestId = NULL): self
	{
		return self::fromParameters([
			'user_id' => $userId,
			'ip_address' => $ipAddress,
			'user_agent' => $userAgent,
			'password_request_id' => $passwordRequestId,
		]);
	}

	/**
	 * @return string
	 */
	public function userId(): string
	{
		return $this->getParam('user_id');
	}

	/**
	 * @return string
	 */
	public function ipAddress(): string
	{
		return $this->getParam('ip_address');
	}

	/**
	 * @return string
	 */
	public function userAgent(): string
	{
		return $this->getParam('user_agent');
	}

	/**
	 * @return string|NULL
	 */
	public function passwordRequestId(): ?string
	{
		return $this->getParam('password_request_id');
	}
}
