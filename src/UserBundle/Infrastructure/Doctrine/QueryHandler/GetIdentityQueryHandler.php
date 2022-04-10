<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\QueryHandler;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\UserBundle\ReadModel\View\IdentityView;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetIdentityQuery;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;

final class GetIdentityQueryHandler implements QueryHandlerInterface
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
	 * @param \SixtyEightPublishers\UserBundle\ReadModel\Query\GetIdentityQuery $query
	 *
	 * @return \SixtyEightPublishers\UserBundle\ReadModel\View\IdentityView|NULL
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function __invoke(GetIdentityQuery $query): ?IdentityView
	{
		$data = $this->em->createQueryBuilder()
			->select('u')
			->from(User::class, 'u')
			->where('u.id = :id')
			->setParameter('id', $query->id())
			->getQuery()
			->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

		return NULL !== $data ? ViewFactory::createIdentityView($data) : NULL;
	}
}
