<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindIdsOfRequestedPasswordChangesQuery;
use function array_column;

final class FindIdsOfRequestedPasswordChangesQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @return array<PasswordRequestId>
     */
    public function __invoke(FindIdsOfRequestedPasswordChangesQuery $query): array
    {
        $data = $this->em->createQueryBuilder()
            ->select('pr.id')
            ->from(PasswordRequest::class, 'pr')
            ->where('LOWER(pr.emailAddress) = LOWER(:emailAddress)')
            ->andWhere('pr.status = :status')
            ->orderBy('pr.requestedAt', 'ASC')
            ->setParameters([
                'emailAddress' => $query->emailAddress,
                'status' => Status::REQUESTED->value,
            ])
            ->getQuery()
            ->getResult();

        return array_column($data, 'id');
    }
}
