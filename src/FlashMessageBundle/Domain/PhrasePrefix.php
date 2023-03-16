<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class PhrasePrefix
{
    public function __construct(
        public readonly string $value,
    ) {}
}
