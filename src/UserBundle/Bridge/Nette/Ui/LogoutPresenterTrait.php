<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Ui;

use Nette\Application\AbortException;
use Nette\Application\ForbiddenRequestException;
use Nette\InvalidStateException;
use Nette\Security\User as NetteUser;
use SixtyEightPublishers\UserBundle\Application\Csrf\CsrfTokenFactoryInterface;
use function assert;
use function sprintf;

/**
 * For links use:
 *
 * <code>
 * $this->link(':My:Logout:Presenter', [
 *      '_sec' => $csrfTokenFactory->create(My\Logout\Presenter::class),
 * ]);
 * </code>
 *
 * @method NetteUser getUser()
 * @method mixed     getParameter($key)
 */
trait LogoutPresenterTrait
{
    protected string $tokenName = '_sec';

    private CsrfTokenFactoryInterface $csrfTokenFactory;

    public function injectCsrfTokenFactory(CsrfTokenFactoryInterface $csrfTokenFactory): void
    {
        $this->csrfTokenFactory = $csrfTokenFactory;
    }

    /**
     * @throws ForbiddenRequestException
     * @throws AbortException
     */
    public function startup(): void
    {
        /** @noinspection PhpUndefinedClassInspection */
        parent::startup();

        $user = $this->getUser();
        assert($user instanceof NetteUser);

        if (!$user->isLoggedIn()) {
            $this->handleUserNotLoggedIn();
        }

        if ($this->getParameter($this->tokenName) !== $this->csrfTokenFactory->create(static::class)) {
            $this->handleInvalidToken();
        }

        $user->logout();
        $this->handleUserLoggedOut();

        throw new InvalidStateException(sprintf(
            'Method %s::handleUserLoggedOut() must redirect when the user is logged out.',
            __CLASS__,
        ));
    }

    /**
     * Do redirect in this method, you can also add flash messages etc.
     *
     * @throws AbortException
     */
    abstract protected function handleUserLoggedOut(): void;

    /**
     * Use can override the default behavior
     *
     * @throws ForbiddenRequestException
     */
    protected function handleUserNotLoggedIn(): void
    {
        throw new ForbiddenRequestException('');
    }

    /**
     * Use can override the default behavior
     *
     * @throws ForbiddenRequestException
     */
    protected function handleInvalidToken(): void
    {
        throw new ForbiddenRequestException('');
    }
}
