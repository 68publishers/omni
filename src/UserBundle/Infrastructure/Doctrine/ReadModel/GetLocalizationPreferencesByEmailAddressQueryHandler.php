<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetLocalizationPreferencesByEmailAddress;
use SixtyEightPublishers\UserBundle\ReadModel\View\LocalizationPreferences;

final class GetLocalizationPreferencesByEmailAddressQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws NonUniqueResultException
     */
    public function __invoke(GetLocalizationPreferencesByEmailAddress $query): ?LocalizationPreferences
    {
        $data = $this->em->createQueryBuilder()
            ->select('u.id, u.locale, u.timezone')
            ->from(User::class, 'u')
            ->where('LOWER(u.emailAddress) = LOWER(:emailAddress)')
            ->andWhere('u.deletedAt IS NULL')
            ->setParameter('emailAddress', $query->emailAddress)
            ->getQuery()
            ->getOneOrNullResult(AbstractQuery::HYDRATE_ARRAY);

        return null !== $data
            ? new LocalizationPreferences(
                userId: $data['id'],
                locale: $data['locale'],
                timezone: $data['timezone'],
            )
            : null;
    }
}
