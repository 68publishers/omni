<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application;

use Nette\Utils\Validators;
use SixtyEightPublishers\MailingBundle\Application\Exception\InvalidEmailAddressException;
use Stringable;
use function sprintf;

final class Address implements Stringable
{
    public function __construct(
        public readonly string $emailAddress,
        public readonly ?string $name = null,
    ) {
        if (!Validators::isEmail($this->emailAddress)) {
            throw InvalidEmailAddressException::create($this->emailAddress);
        }
    }

    public function __toString(): string
    {
        return null !== $this->name ? sprintf('%s<%s>', $this->emailAddress, $this->name) : $this->emailAddress;
    }
}
