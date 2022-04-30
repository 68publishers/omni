<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\AbstractCommand;

final class RequestPasswordChangeCommand extends AbstractCommand
{
	/**
	 * @param string      $emailAddress
	 * @param string      $ipAddress
	 * @param string      $userAgent
	 * @param string|NULL $passwordRequestId
	 *
	 * @return static
	 */
	public static function create(string $emailAddress, string $ipAddress, string $userAgent, ?string $passwordRequestId = NULL): self
	{
		return self::fromParameters([
			'email_address' => $emailAddress,
			'ip_address' => $ipAddress,
			'user_agent' => $userAgent,
			'password_request_id' => $passwordRequestId,
		]);
	}

	/**
	 * @return string
	 */
	public function emailAddress(): string
	{
		return $this->getParam('email_address');
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
