<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectSetInterface;

abstract class AbstractValueObjectSetType extends JsonType
{
	protected string $valueObjectClassname;

	/**
	 * {@inheritDoc}
	 */
	public function getName(): string
	{
		return $this->valueObjectClassname;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToPHPValue($value, AbstractPlatform $platform): ?ValueObjectSetInterface
	{
		$value = parent::convertToPHPValue($value, $platform);

		return NULL !== $value ? call_user_func([$this->valueObjectClassname, 'reconstitute'], (array) $value) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (!$value instanceof ValueObjectSetInterface) {
			throw ConversionException::conversionFailed($value, $this->valueObjectClassname);
		}

		return parent::convertToDatabaseValue($value->toArray(), $platform);
	}
}
