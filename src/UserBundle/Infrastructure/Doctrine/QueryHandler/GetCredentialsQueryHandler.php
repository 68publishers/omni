<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\QueryHandler;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\UserBundle\ReadModel\View\CredentialsView;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetCredentialsQuery;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;

final class GetCredentialsQueryHandler implements QueryHandlerInterface
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
	 * @param \SixtyEightPublishers\UserBundle\ReadModel\Query\GetCredentialsQuery $query
	 *
	 * @return \SixtyEightPublishers\UserBundle\ReadModel\View\CredentialsView|NULL
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function __invoke(GetCredentialsQuery $query): ?CredentialsView
	{
		$data = $this->em->createQueryBuilder()
			->select('u.id, u.username, u.password')
			->from(User::class, 'u')
			->where('LOWER(u.username) = LOWER(:username)')
			->andWhere('u.deletedAt IS NULL')
			->setParameter('username', $query->username())
			->getQuery()
			->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

		return NULL !== $data ? CredentialsView::fromCredentials($data['id'], $data['username'], $data['password']) : NULL;
	}
}
