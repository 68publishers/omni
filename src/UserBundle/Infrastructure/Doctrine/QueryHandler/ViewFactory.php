<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\QueryHandler;

use SixtyEightPublishers\UserBundle\Domain\Dto\Name;
use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\UserBundle\ReadModel\View\IdentityView;

final class ViewFactory
{
	public function __construct()
	{
	}

	/**
	 * @param array $data
	 *
	 * @return \SixtyEightPublishers\UserBundle\ReadModel\View\UserView
	 */
	public static function createUserView(array $data): UserView
	{
		$data['name'] = Name::fromValues($data['name.firstname'] ?? '', $data['name.surname'] ?? '');

		return UserView::fromArray($data);
	}

	/**
	 * @param array $data
	 *
	 * @return \SixtyEightPublishers\UserBundle\ReadModel\View\IdentityView
	 */
	public static function createIdentityView(array $data): IdentityView
	{
		$data['name'] = Name::fromValues($data['name.firstname'] ?? '', $data['name.surname'] ?? '');

		return IdentityView::fromArray($data);
	}
}
