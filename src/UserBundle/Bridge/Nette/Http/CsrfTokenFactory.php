<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Http;

use Nette\Http\Session;
use SixtyEightPublishers\UserBundle\Application\Csrf\AbstractCsrfTokenFactory;

final class CsrfTokenFactory extends AbstractCsrfTokenFactory
{
    public function __construct(
        private readonly Session $session,
    ) {}

    protected function retrieveToken(): ?string
    {
        return $this->session->getSection(__CLASS__)->get('token');
    }

    protected function storeToken(string $token): void
    {
        $this->session->getSection(__CLASS__)->set('token', $token);
    }

    protected function getSessionId(): string
    {
        return $this->session->getId();
    }
}
