<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use function get_class;
use function gettype;
use function is_object;
use function sprintf;

final class ValueObjectSetException extends DomainException
{
    /**
     * @param class-string      $valueObjectClassname
     * @param class-string|null $itemValueObjectClassname
     */
    private function __construct(
        string $message,
        public readonly string $valueObjectClassname,
        public readonly ?string $itemValueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function undeclaredItemType(string $valueObjectClassname): self
    {
        return new self(sprintf(
            'Item type must be declared, please override the static property %s::$itemClassname',
            $valueObjectClassname,
        ), $valueObjectClassname, null);
    }

    /**
     * @param class-string $valueObjectClassname
     * @param class-string $itemValueObjectClassname
     */
    public static function declaredItemTypeMustBeValueObjectImplementor(string $valueObjectClassname, string $itemValueObjectClassname): self
    {
        return new self(sprintf(
            'Invalid item type %s declared for a value object set %s. Item type must implements an interface %s.',
            $itemValueObjectClassname,
            $valueObjectClassname,
            ValueObjectInterface::class,
        ), $valueObjectClassname, $itemValueObjectClassname);
    }

    /**
     * @param class-string $valueObjectClassname
     * @param class-string $itemValueObjectClassname
     */
    public static function invalidItemPassed(string $valueObjectClassname, string $itemValueObjectClassname, mixed $passedItem): self
    {
        $passedType = is_object($passedItem) ? ('instance of ' . get_class($passedItem)) : gettype($passedItem);
        $passedType = ['boolean' => 'bool', 'integer' => 'int', 'double' => 'float', 'NULL' => 'null'][$passedType] ?? $passedType;

        return new self(sprintf(
            'Invalid item passed into a value object of type %s. Expected type of items is %s, %s passed.',
            $valueObjectClassname,
            $itemValueObjectClassname,
            $passedType,
        ), $valueObjectClassname, $itemValueObjectClassname);
    }
}
