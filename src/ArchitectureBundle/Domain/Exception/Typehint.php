<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use stdClass;
use function get_class;
use function gettype;
use function implode;
use function is_array;
use function is_int;
use function is_object;

final class Typehint
{
    public function __construct(
        public readonly string $value,
        public readonly bool $isInstance,
    ) {}

    public static function fromVariable(mixed $variable): self
    {
        [$value, $isInstance] = self::getVariableType(
            variable: $variable,
        );

        return new self(
            value: $value,
            isInstance: $isInstance,
        );
    }

    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @return array{
     *     0: string,
     *     1: bool,
     * }
     */
    private static function getVariableType(mixed $variable): array
    {
        if (is_object($variable) && stdClass::class !== get_class($variable)) {
            $type = get_class($variable);
            $isInstance = true;
        } else {
            $type = gettype($variable);
            $isInstance = false;
        }

        $type = ['boolean' => 'bool', 'integer' => 'int', 'double' => 'float', 'NULL' => 'null'][$type] ?? $type;

        if ('array' === $type && is_array($variable)) {
            $structure = [];

            foreach ($variable as $k => $v) {
                $structure[] = (is_int($k) ? $k : ($k . "'$k'")) . ': ' . self::getVariableType($v)[0];
            }

            $type = 'array{' . implode(', ', $structure) . '}';
        }

        return [
            $type,
            $isInstance,
        ];
    }
}
