<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Ramsey\Uuid\Uuid;
use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;
use Doctrine\DBAL\Types\GuidType;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AbstractUuidIdentity;

abstract class AbstractUuidIdentityType extends GuidType
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
	public function convertToPHPValue($value, AbstractPlatform $platform): ?AbstractUuidIdentity
	{
		$uuid = $this->convertToUuid($value);

		return NULL !== $uuid ? call_user_func([$this->valueObjectClassname, 'fromUuid'], $uuid) : NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
	{
		if (NULL === $value || '' === $value) {
			return NULL;
		}

		if ($value instanceof AbstractUuidIdentity) {
			$value = $value->id();
		}

		if ($value instanceof UuidInterface || ((is_string($value) || method_exists($value, '__toString')) && Uuid::isValid((string) $value))) {
			return (string) $value;
		}

		throw ConversionException::conversionFailed($value, $this->valueObjectClassname);
	}

	/**
	 * @param mixed $value
	 *
	 * @return \Ramsey\Uuid\UuidInterface|NULL
	 * @throws \Doctrine\DBAL\Types\ConversionException
	 */
	private function convertToUuid($value): ?UuidInterface
	{
		if ($value === NULL || '' === $value) {
			return NULL;
		}

		if ($value instanceof UuidInterface) {
			return $value;
		}

		try {
			$uuid = Uuid::fromString($value);
		} catch (InvalidArgumentException $e) {
			throw ConversionException::conversionFailed($value, $this->valueObjectClassname);
		}

		return $uuid;
	}

	/**
	 * {@inheritDoc}
	 */
	public function requiresSQLCommentHint(AbstractPlatform $platform): bool
	{
		return TRUE;
	}
}
