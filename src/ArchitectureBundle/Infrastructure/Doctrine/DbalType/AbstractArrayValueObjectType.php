<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ArrayValueObjectInterface;

abstract class AbstractArrayValueObjectType extends JsonType
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
	public function convertToPHPValue($value, AbstractPlatform $platform): ?ArrayValueObjectInterface
	{
		$value = parent::convertToPHPValue($value, $platform);

		return NULL !== $value ? call_user_func([$this->valueObjectClassname, 'fromArray'], (array) $value) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (NULL !== $value && !$value instanceof ArrayValueObjectInterface) {
			throw ConversionException::conversionFailed($value, $this->valueObjectClassname);
		}

		return parent::convertToDatabaseValue(NULL !== $value ? $value->values() : NULL, $platform);
	}

	/**
	 * {@inheritDoc}
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return TRUE;
	}
}
