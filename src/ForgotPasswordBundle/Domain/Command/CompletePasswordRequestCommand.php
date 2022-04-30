<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\AbstractCommand;

final class CompletePasswordRequestCommand extends AbstractCommand
{
	/**
	 * @param string $passwordRequestId
	 * @param string $password
	 * @param string $ipAddress
	 * @param string $userAgent
	 *
	 * @return static
	 */
	public static function create(string $passwordRequestId, string $password, string $ipAddress, string $userAgent): self
	{
		return self::fromParameters([
			'password_request_id' => $passwordRequestId,
			'password' => $password,
			'ip_address' => $ipAddress,
			'user_agent' => $userAgent,
		]);
	}

	/**
	 * @return string
	 */
	public function passwordRequestId(): string
	{
		return $this->getParam('password_request_id');
	}

	/**
	 * @return string
	 */
	public function password(): string
	{
		return $this->getParam('password');
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
}
