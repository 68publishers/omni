<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\HttpLink;

use RuntimeException;
use function get_class;
use function gettype;
use function implode;
use function is_object;
use function is_scalar;
use function sprintf;

final class UnableToCreateLinkException extends RuntimeException
{
    public static function create(LinkInterface $link): self
    {
        $params = [];

        foreach ($link->getParameters() as $name => $value) {
            $params[] = sprintf(
                '%s: %s',
                $name,
                is_scalar($value) ? $value : (is_object($value) ? get_class($value) : gettype($value)),
            );
        }

        return new self(sprintf(
            'Unable to create link "%s" with args [%s].',
            $link->getName(),
            implode(', ', $params),
        ));
    }
}
