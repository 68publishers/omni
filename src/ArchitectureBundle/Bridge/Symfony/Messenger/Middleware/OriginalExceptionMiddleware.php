<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class OriginalExceptionMiddleware implements MiddlewareInterface
{
	/**
	 * @param \Symfony\Component\Messenger\Envelope                  $envelope
	 * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
	 *
	 * @return \Symfony\Component\Messenger\Envelope
	 * @throws \Throwable
	 */
	public function handle(Envelope $envelope, StackInterface $stack): Envelope
	{
		try {
			$envelope = $stack->next()->handle($envelope, $stack);
		} catch (HandlerFailedException $exception) {
			throw $exception->getNestedExceptions()[0] ?? $exception;
		}

		return $envelope;
	}
}
