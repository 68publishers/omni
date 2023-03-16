<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserIdByEmailAddressQuery;

final class GetUserIdByEmailAddressQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(GetUserIdByEmailAddressQuery $query): ?UserId
    {
        $data = $this->em->createQueryBuilder()
            ->select('u.id')
            ->from(User::class, 'u')
            ->where('LOWER(u.emailAddress) = LOWER(:emailAddress)')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('emailAddress', $query->emailAddress)
            ->getQuery()
            ->getOneOrNullResult();

        return null !== $data ? $data['id'] : null;
    }
}
