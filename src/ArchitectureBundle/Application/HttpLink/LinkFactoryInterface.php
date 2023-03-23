<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\HttpLink;

interface LinkFactoryInterface
{
    /**
     * @throws UnableToCreateLinkException
     */
    public function create(LinkInterface $link): string;
}
