<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ArrayValueObjectInterface;

abstract class AbstractArrayValueObjectType extends JsonType
{
	protected string $dtoClassname;

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->dtoClassname;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform): ?ArrayValueObjectInterface
	{
		$value = parent::convertToPHPValue($value, $platform);

		return NULL !== $value ? call_user_func([$this->dtoClassname, 'fromArray'], (array) $value) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (!$value instanceof ArrayValueObjectInterface) {
			throw ConversionException::conversionFailed($value, $this->dtoClassname);
		}

		return parent::convertToDatabaseValue($value->values(), $platform);
	}
}
