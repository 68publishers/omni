<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\ReadModel;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\IsPossibleToCompletePasswordChangeRequestQuery;

final class IsPossibleToCompletePasswordChangeRequestQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(IsPossibleToCompletePasswordChangeRequestQuery $query): bool
    {
        if (!PasswordRequestId::isValid($query->passwordRequestId)) {
            return false;
        }

        $count = (int) $this->em->createQueryBuilder()
            ->select('COUNT(pr.id)')
            ->from(PasswordRequest::class, 'pr')
            ->where('pr.id = :id')
            ->andWhere('pr.status = :status')
            ->andWhere('pr.expiredAt < :now')
            ->setParameters([
                'id' => $query->passwordRequestId,
                'status' => Status::REQUESTED->value,
                'now' => new DateTimeImmutable('now', new DateTimeZone('UTC')),
            ])
            ->getQuery()
            ->getSingleScalarResult();

        return 0 < $count;
    }
}
