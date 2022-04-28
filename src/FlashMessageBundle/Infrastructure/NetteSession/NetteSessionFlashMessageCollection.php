<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Infrastructure\NetteSession;

use Nette\Http\Session;
use Nette\Http\SessionSection;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageId;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface;

final class NetteSessionFlashMessageCollection implements FlashMessageCollectionInterface
{
	private SessionSection $section;

	/**
	 * @param \Nette\Http\Session $session
	 */
	public function __construct(Session $session)
	{
		$this->section = $session->getSection(str_replace('\\', '.', FlashMessage::class));
	}

	/**
	 * {@inheritDoc}
	 */
	public function add(FlashMessage $flashMessage): void
	{
		if (!$this->section->offsetExists($flashMessage->id()->toString())) {
			$this->section->set($flashMessage->id()->toString(), $flashMessage);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove(FlashMessageId $id): void
	{
		if ($this->section->offsetExists($id->toString())) {
			$this->section->remove($id->toString());
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function all(): array
	{
		return array_values(iterator_to_array($this->section));
	}
}
