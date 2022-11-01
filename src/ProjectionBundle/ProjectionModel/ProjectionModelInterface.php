<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

interface ProjectionModelInterface
{
	public static function projectionClassname(): string;

	/**
	 * @throws \Throwable
	 */
	public function reset(): void;

	public function insert(array $values): void;

	public function update(array $values, array $criteria): void;

	public function delete(array $criteria): void;

	public function execute(string $action, ...$args): void;
}
