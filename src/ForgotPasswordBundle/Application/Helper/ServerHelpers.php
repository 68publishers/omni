<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\Helper;

final class ServerHelpers
{
	private function __construct()
	{
	}

	/**
	 * @return string
	 */
	public static function getIpAddress(): string
	{
		return (string) ($_SERVER['HTTP_CLIENT_IP'] ?? ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']));
	}

	/**
	 * @return string
	 */
	public static function getUserAgent(): string
	{
		return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
	}
}
