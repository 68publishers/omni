<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetIdentityDataQuery;
use SixtyEightPublishers\UserBundle\ReadModel\View\IdentityData;

final class GetIdentityDataQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(GetIdentityDataQuery $query): ?IdentityData
    {
        if (!UserId::isValid($query->userId)) {
            return null;
        }

        $data = $this->em->createQueryBuilder()
            ->select('u.id, u.createdAt, u.username, u.emailAddress, u.active, u.name.firstname, u.name.surname, u.roles, u.locale, u.timezone, u.attributes')
            ->from(User::class, 'u')
            ->where('u.id = :id')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('id', $query->userId)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        return null !== $data
            ? new IdentityData(
                id: $data['id'],
                createdAt: $data['createdAt'],
                username: $data['username'],
                emailAddress: $data['emailAddress'],
                active: $data['active'],
                name: new Name($data['name.firstname'], $data['name.surname']),
                roles: $data['roles'],
                locale: $data['locale'],
                timezone: $data['timezone'],
                attributes: $data['attributes'],
            )
            : null;
    }
}
