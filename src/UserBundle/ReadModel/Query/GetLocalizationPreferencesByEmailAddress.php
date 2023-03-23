<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

/**
 * Returns ?LocalizationPreferences
 */
final class GetLocalizationPreferencesByEmailAddress implements QueryInterface
{
    public function __construct(
        public readonly string $emailAddress,
    ) {}
}
