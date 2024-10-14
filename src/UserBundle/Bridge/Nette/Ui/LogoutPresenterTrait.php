<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Ui;

use Nette\InvalidStateException;
use Nette\Security\User as NetteUser;
use Nette\Application\ForbiddenRequestException;
use SixtyEightPublishers\UserBundle\Application\Csrf\CsrfTokenFactoryInterface;

/**
 * For links use:
 *
 * <code>
 * $this->link(':My:Logout:Presenter', [
 *      '_sec' => $csrfTokenFactory->create(My\Logout\Presenter::class),
 * ]);
 * </code>
 *
 *
 * @method NetteUser getUser()
 * @method mixed     getParameter($key)
 */
trait LogoutPresenterTrait
{
	protected string $tokenName = '_sec';

	private CsrfTokenFactoryInterface $csrfTokenFactory;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Application\Csrf\CsrfTokenFactoryInterface $csrfTokenFactory
	 *
	 * @return void
	 */
	public function injectCsrfTokenFactory(CsrfTokenFactoryInterface $csrfTokenFactory): void
	{
		$this->csrfTokenFactory = $csrfTokenFactory;
	}

	/**
	 * @return void
	 * @throws \Nette\Application\ForbiddenRequestException
	 * @throws \Nette\Application\AbortException
	 */
	public function startup(): void
	{
		/** @noinspection PhpUndefinedClassInspection */
		parent::startup();

		$user = $this->getUser();
		assert($user instanceof NetteUser);

		if (!$user->isLoggedIn()) {
			$this->userNotLoggedInHandler();
		}

		if ($this->getParameter($this->tokenName) !== $this->csrfTokenFactory->create(static::class)) {
			$this->invalidTokenHandler();
		}

		$user->logout();
		$this->userLoggedOutHandler();

		throw new InvalidStateException(sprintf(
			'Method %s::handleUserLoggedOut() must redirect when the user is logged out.',
			__CLASS__
		));
	}

	/**
	 * Do redirect in this method, you can also add flash messages etc.
	 *
	 * @return void
	 * @throws \Nette\Application\AbortException
	 */
	abstract protected function userLoggedOutHandler(): void;

	/**
	 * Use can override the default behavior
	 *
	 * @return void
	 * @throws \Nette\Application\ForbiddenRequestException
	 */
	protected function userNotLoggedInHandler(): void
	{
		throw new ForbiddenRequestException('');
	}

	/**
	 * Use can override the default behavior
	 *
	 * @return void
	 * @throws \Nette\Application\ForbiddenRequestException
	 */
	protected function invalidTokenHandler(): void
	{
		throw new ForbiddenRequestException('');
	}
}
