<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

/**
 * Returns ?MailSource
 */
final class GetMailSourceByCodeQuery implements QueryInterface
{
    public function __construct(
        public readonly string $code,
        public readonly string $locale,
    ) {}
}
