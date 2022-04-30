<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\QueryHandler;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\GetPasswordRequestByIdQuery;

final class GetPasswordRequestByIdQueryHandler implements QueryHandlerInterface
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
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\GetPasswordRequestByIdQuery $query
	 *
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView|NULL
	 * @throws \Doctrine\ORM\NonUniqueResultException
	 */
	public function __invoke(GetPasswordRequestByIdQuery $query): ?PasswordRequestView
	{
		$data = $this->em->createQueryBuilder()
			->select('pr')
			->from(PasswordRequest::class, 'pr')
			->where('pr.id = :id')
			->setParameter('id', $query->id())
			->getQuery()
			->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

		return NULL !== $data ? ViewFactory::createPasswordRequestView($data) : NULL;
	}
}
