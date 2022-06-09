<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetIdentityQuery;
use SixtyEightPublishers\UserBundle\Application\Exception\IdentityException;

class Identity
{
	protected UserId $id;

	protected ?QueryBusInterface $queryBus = NULL;

	protected ?UserView $data = NULL;

	protected bool $dataLoaded = FALSE;

	protected function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId $id
	 *
	 * @return static
	 */
	public static function createSleeping(UserId $id): self
	{
		$identity = new static();
		$identity->id = $id;

		return $identity;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId
	 */
	public function id(): UserId
	{
		return $this->id;
	}

	/**
	 * @return \SixtyEightPublishers\UserBundle\ReadModel\View\UserView
	 * @throws \SixtyEightPublishers\UserBundle\Application\Exception\IdentityException
	 */
	public function data(): UserView
	{
		if ($this->dataLoaded) {
			return $this->data;
		}

		if (NULL === $this->queryBus) {
			throw IdentityException::unableToRetrieveDataFromSleepingIdentity();
		}

		$data = $this->queryBus->dispatch(GetIdentityQuery::create($this->id()->toString()));

		if (!$data instanceof UserView) {
			throw IdentityException::dataNotFound($this->id());
		}

		$this->dataLoaded = TRUE;

		return $this->data = $data;
	}

	/**
	 * @return void
	 */
	public function reload(): void
	{
		$this->dataLoaded = FALSE;
		$this->data = NULL;
	}

	/**
	 * @return $this
	 */
	protected function sleep(): self
	{
		return static::createSleeping($this->id);
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface $queryBus
	 *
	 * @return $this
	 */
	protected function wakeup(QueryBusInterface $queryBus): self
	{
		$identity = static::createSleeping($this->id);
		$identity->queryBus = $queryBus;

		return $identity;
	}
}
