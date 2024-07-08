<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use RuntimeException;
use function enum_exists;
use function sprintf;

abstract class AbstractEnumType extends Type
{
    public function getName(): string
    {
        return $this->getEnumsClassname();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string|int|null
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }

        return null;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?BackedEnum
    {
        if (null === $value) {
            return null;
        }

        $enumClassname = $this->getEnumsClassname();

        if (false === enum_exists($enumClassname, true)) {
            throw new RuntimeException(sprintf(
                'The class %s is not an enum.',
                $enumClassname,
            ));
        }

        return $enumClassname::tryFrom($value);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    /**
     * @return class-string<BackedEnum>
     */
    abstract protected function getEnumsClassname(): string;
}
