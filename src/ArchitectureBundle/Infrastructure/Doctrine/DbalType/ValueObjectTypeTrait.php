<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use function is_scalar;
use function sprintf;
use function var_export;

trait ValueObjectTypeTrait
{
    public function getName(): string
    {
        return $this->getValueObjectClassname();
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        $value = parent::convertToPHPValue($value, $platform);
        $classname = $this->getValueObjectClassname();

        try {
            return $classname::fromNative($value);
        } catch (DomainException $e) {
            throw ConversionException::conversionFailed(
                is_scalar($value) ? (string) $value : var_export($value, true),
                $classname,
                $e,
            );
        }
    }

    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (!$value instanceof ValueObjectInterface) {
            throw ConversionException::conversionFailedSerialization($value, 'native', sprintf(
                'Value is not instance of %s',
                ValueObjectInterface::class,
            ));
        }

        try {
            $value = $value->toNative();
        } catch (DomainException $e) {
            throw ConversionException::conversionFailedSerialization($value, 'native', $e->getMessage());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return class-string<ValueObjectInterface>
     */
    abstract protected function getValueObjectClassname(): string;
}
