<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\IntegerType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\IntegerValueObjectInterface;

abstract class AbstractIntegerValueObjectType extends IntegerType
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
	public function convertToPHPValue($value, AbstractPlatform $platform): ?IntegerValueObjectInterface
	{
		$value = parent::convertToPHPValue($value, $platform);

		return NULL !== $value ? call_user_func([$this->valueObjectClassname, 'fromValue'], $value) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?int
	{
		if (NULL !== $value && !is_int($value) && !$value instanceof IntegerValueObjectInterface) {
			throw ConversionException::conversionFailed($value, $this->valueObjectClassname);
		}

		if ($value instanceof IntegerValueObjectInterface) {
			$value = $value->value();
		}

		return parent::convertToDatabaseValue($value, $platform);
	}

	/**
	 * {@inheritDoc}
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return TRUE;
	}
}
