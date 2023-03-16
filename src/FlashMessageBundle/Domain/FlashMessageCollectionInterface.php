<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

interface FlashMessageCollectionInterface
{
    public function add(FlashMessage $flashMessage): void;

    public function remove(FlashMessageId $id): void;

    /**
     * @return array<FlashMessage>
     */
    public function all(): array;
}
