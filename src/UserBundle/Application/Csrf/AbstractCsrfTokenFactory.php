<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Csrf;

use Nette\Utils\Random;
use function base64_encode;
use function hash_hmac;
use function mb_substr;
use function session_id;
use function str_replace;

abstract class AbstractCsrfTokenFactory implements CsrfTokenFactoryInterface
{
    public function create(string $prefix = ''): string
    {
        $token = $this->retrieveToken();

        if (null === $token) {
            $token = Random::generate();

            $this->storeToken($token);
        }

        $hash = hash_hmac('sha1', $prefix . $this->getSessionId(), $token, true);

        return str_replace('/', '_', mb_substr(base64_encode($hash), 0, 8));
    }

    abstract protected function retrieveToken(): ?string;

    abstract protected function storeToken(string $token): void;

    protected function getSessionId(): string
    {
        return (string) session_id();
    }
}
