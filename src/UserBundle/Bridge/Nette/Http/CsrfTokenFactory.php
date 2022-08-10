<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Http;

use Nette\Http\Session;
use Nette\Utils\Random;
use SixtyEightPublishers\UserBundle\Application\Csrf\CsrfTokenFactoryInterface;

final class CsrfTokenFactory implements CsrfTokenFactoryInterface
{
	private Session $session;

	/**
	 * @param \Nette\Http\Session $session
	 */
	public function __construct(Session $session)
	{
		$this->session = $session;
	}

	/**
	 * {@inheritdoc}
	 */
	public function create(string $component = ''): string
	{
		$section = $this->session->getSection(__CLASS__);

		if (!isset($section['token'])) {
			$section['token'] = Random::generate(10);
		}

		$hash = hash_hmac('sha1', $component . $this->session->getId(), $section['token'], TRUE);

		return str_replace('/', '_', mb_substr(base64_encode($hash), 0, 8));
	}
}
