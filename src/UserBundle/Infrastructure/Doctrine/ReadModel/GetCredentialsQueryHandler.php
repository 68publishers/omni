<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetCredentialsQuery;
use SixtyEightPublishers\UserBundle\ReadModel\View\Credentials;

final class GetCredentialsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(GetCredentialsQuery $query): ?Credentials
    {
        $viewClassname = Credentials::class;

        return $this->em->createQueryBuilder()
            ->select("NEW $viewClassname(u.id, u.username, u.password)")
            ->from(User::class, 'u')
            ->where('LOWER(u.username) = LOWER(:username)')
            ->andWhere('u.deletedAt IS NULL')
            ->andWhere('u.active = true')
            ->setParameter('username', $query->username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
