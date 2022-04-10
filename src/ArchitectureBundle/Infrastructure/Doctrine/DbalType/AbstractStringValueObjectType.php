<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\StringValueObjectInterface;

abstract class AbstractStringValueObjectType extends StringType
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
	public function convertToPHPValue($value, AbstractPlatform $platform): ?StringValueObjectInterface
	{
		$value = parent::convertToPHPValue($value, $platform);

		return NULL !== $value ? call_user_func([$this->dtoClassname, 'fromValue'], $value) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (!is_string($value) && !$value instanceof StringValueObjectInterface) {
			throw ConversionException::conversionFailed($value, $this->dtoClassname);
		}

		if ($value instanceof StringValueObjectInterface) {
			$value = $value->value();
		}

		return parent::convertToDatabaseValue($value, $platform);
	}
}
