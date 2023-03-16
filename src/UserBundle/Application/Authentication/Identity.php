<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\Application\Exception\IdentityException;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetIdentityDataQuery;
use SixtyEightPublishers\UserBundle\ReadModel\View\IdentityData;

class Identity
{
    protected string $id;

    protected ?QueryBusInterface $queryBus = null;

    protected ?IdentityData $data = null;

    protected bool $dataLoaded = false;

    protected function __construct() {}

    public static function createSleeping(string $id): static
    {
        $identity = new static(); // @phpstan-ignore-line
        $identity->id = $id;

        return $identity;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @throws IdentityException
     */
    public function getData(): IdentityData
    {
        if ($this->dataLoaded) {
            if (null === $this->data) {
                throw IdentityException::dataNotFound($this->getId());
            }

            return $this->data;
        }

        if (null === $this->queryBus) {
            throw IdentityException::unableToRetrieveDataFromSleepingIdentity();
        }

        $data = $this->queryBus->dispatch(new GetIdentityDataQuery($this->getId()));

        if (!$data instanceof IdentityData) {
            throw IdentityException::dataNotFound($this->getId());
        }

        $this->dataLoaded = true;

        return $this->data = $data;
    }

    public function reload(): void
    {
        $this->dataLoaded = false;
        $this->data = null;
    }

    protected function sleep(): self
    {
        return static::createSleeping($this->id);
    }

    protected function wakeup(QueryBusInterface $queryBus): self
    {
        $identity = static::createSleeping($this->id);
        $identity->queryBus = $queryBus;

        return $identity;
    }
}
