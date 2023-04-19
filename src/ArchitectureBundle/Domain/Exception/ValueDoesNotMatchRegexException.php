<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class ValueDoesNotMatchRegexException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly string $value,
        public readonly string $regex,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function create(string $regex, string $value, string $valueObjectClassname): self
    {
        return new self(sprintf(
            'Value "%s" passed into a value object of the type %s does not match the regex %s',
            $value,
            $valueObjectClassname,
            $regex,
        ), $value, $regex, $valueObjectClassname);
    }
}
