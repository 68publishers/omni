<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Infrastructure\Filesystem\Locator;

interface MailSourceLocatorInterface
{
    public function locale(string $code, string $locale, ?string $postfix = null): ?string;
}
