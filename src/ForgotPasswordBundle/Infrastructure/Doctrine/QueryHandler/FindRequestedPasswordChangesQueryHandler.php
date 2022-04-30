<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\QueryHandler;

use Generator;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\Status;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\BatchGeneratorFactory;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindRequestedPasswordChangesQuery;

final class FindRequestedPasswordChangesQueryHandler implements QueryHandlerInterface
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
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindRequestedPasswordChangesQuery $query
	 *
	 * @return \Generator
	 */
	public function __invoke(FindRequestedPasswordChangesQuery $query): Generator
	{
		$doctrineQuery = $this->em->createQueryBuilder()
			->select('pr')
			->from(PasswordRequest::class, 'pr')
			->where('pr.emailAddress = :emailAddress')
			->andWhere('pr.status = :status')
			->orderBy('pr.requestedAt', 'ASC')
			->setParameters([
				'emailAddress' => $query->emailAddress(),
				'status' => Status::REQUESTED()->value(),
			])
			->getQuery()
			->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);

		return BatchGeneratorFactory::create($query, $doctrineQuery, static fn (array $data): PasswordRequestView => ViewFactory::createPasswordRequestView($data));
	}
}
