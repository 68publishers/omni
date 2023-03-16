<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Infrastructure\NetteSession;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageId;
use function array_values;
use function iterator_to_array;
use function str_replace;

final class NetteSessionFlashMessageCollection implements FlashMessageCollectionInterface
{
    private SessionSection $section;

    public function __construct(Session $session)
    {
        $this->section = $session->getSection(str_replace('\\', '.', FlashMessage::class));
    }

    public function add(FlashMessage $flashMessage): void
    {
        if (null === $this->section->get($flashMessage->getId()->toNative())) {
            $this->section->set($flashMessage->getId()->toNative(), $flashMessage);
        }
    }

    public function remove(FlashMessageId $id): void
    {
        if ($this->section->get($id->toNative())) {
            $this->section->remove($id->toNative());
        }
    }

    public function all(): array
    {
        return array_values(iterator_to_array($this->section));
    }
}
