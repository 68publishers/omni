<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\CompositeValueObjectTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class DeviceInfo implements ValueObjectInterface
{
    use CompositeValueObjectTrait;

    public function __construct(
        private readonly IpAddress $ipAddress,
        private readonly UserAgent $userAgent,
    ) {}

    protected static function fromNativeFactory(callable $factory): static
    {
        return new self(
            $factory(IpAddress::class, 'ip_address'),
            $factory(UserAgent::class, 'user_agent'),
        );
    }

    public function getIpAddress(): IpAddress
    {
        return $this->ipAddress;
    }

    public function getUserAgent(): UserAgent
    {
        return $this->userAgent;
    }
}
