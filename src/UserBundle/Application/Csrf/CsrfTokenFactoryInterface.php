<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Csrf;

interface CsrfTokenFactoryInterface
{
    public function create(string $prefix = ''): string;
}
