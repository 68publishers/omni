<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\AbstractCommand;

final class DeleteUserCommand extends AbstractCommand
{
	/**
	 * @param string $userId
	 *
	 * @return static
	 */
	public static function create(string $userId): self
	{
		return self::fromParameters([
			'user_id' => $userId,
		]);
	}

	/**
	 * @return string
	 */
	public function userId(): string
	{
		return $this->getParam('user_id');
	}
}
