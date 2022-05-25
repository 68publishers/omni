<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\QueryHandler;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByEmailAddressQuery;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;

final class GetUserByEmailAddressQueryHandler implements QueryHandlerInterface
{
	private EntityManagerInterface $em;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByEmailAddressQuery $query
	 *
	 * @return \SixtyEightPublishers\UserBundle\ReadModel\View\UserView|NULL
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function __invoke(GetUserByEmailAddressQuery $query): ?UserView
	{
		$data = $this->em->createQueryBuilder()
			->select('u')
			->from(User::class, 'u')
			->where('LOWER(u.emailAddress) = LOWER(:emailAddress)')
			->andWhere('u.deletedAt IS NULL')
			->setParameter('emailAddress', $query->emailAddress())
			->getQuery()
			->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

		return NULL !== $data ? ViewFactory::createUserView($data) : NULL;
	}
}
