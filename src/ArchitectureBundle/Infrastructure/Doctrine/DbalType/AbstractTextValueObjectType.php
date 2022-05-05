<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\TextType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueObjectInterface;

abstract class AbstractTextValueObjectType extends TextType
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
	public function convertToPHPValue($value, AbstractPlatform $platform): ?StringValueObjectInterface
	{
		$value = parent::convertToPHPValue($value, $platform);

		return NULL !== $value ? call_user_func([$this->valueObjectClassname, 'fromValue'], $value) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (NULL !== $value && !is_string($value) && !$value instanceof StringValueObjectInterface) {
			throw ConversionException::conversionFailed($value, $this->valueObjectClassname);
		}

		if ($value instanceof StringValueObjectInterface) {
			$value = $value->value();
		}

		return parent::convertToDatabaseValue($value, $platform);
	}
}
