<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\ReadModel;

use Generator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindRequestedPasswordChangesQuery;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel\BatchGeneratorFactory;

final class FindRequestedPasswordChangesQueryHandler implements QueryHandlerInterface
{
	private EntityManagerInterface $em;

	private BatchGeneratorFactory $batchGeneratorFactory;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface                                                             $em
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel\BatchGeneratorFactory $batchGeneratorFactory
	 */
	public function __construct(EntityManagerInterface $em, BatchGeneratorFactory $batchGeneratorFactory)
	{
		$this->em = $em;
		$this->batchGeneratorFactory = $batchGeneratorFactory;
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindRequestedPasswordChangesQuery $query
	 *
	 * @return \Generator
	 */
	public function __invoke(FindRequestedPasswordChangesQuery $query): Generator
	{
		$doctrineQuery = $this->em->createQueryBuilder()
			->select('pr')
			->from(PasswordRequest::class, 'pr')
			->where('LOWER(pr.emailAddress) = LOWER(:emailAddress)')
			->andWhere('pr.status = :status')
			->orderBy('pr.requestedAt', 'ASC')
			->setParameters([
				'emailAddress' => $query->emailAddress(),
				'status' => Status::REQUESTED()->value(),
			])
			->getQuery()
			->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);

		return $this->batchGeneratorFactory->create($query, $doctrineQuery, PasswordRequestView::class);
	}
}
