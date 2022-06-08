<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewFactoryInterface;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\GetPasswordRequestByIdQuery;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel\DoctrineViewData;

final class GetPasswordRequestByIdQueryHandler implements QueryHandlerInterface
{
	private EntityManagerInterface $em;

	private ViewFactoryInterface $viewFactory;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface                                         $em
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewFactoryInterface $viewFactory
	 */
	public function __construct(EntityManagerInterface $em, ViewFactoryInterface $viewFactory)
	{
		$this->em = $em;
		$this->viewFactory = $viewFactory;
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

		return NULL !== $data ? $this->viewFactory->create(PasswordRequestView::class, DoctrineViewData::create($data)) : NULL;
	}
}
