<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Subscribers\PasswordRequest;

use DateTimeZone;
use Exception;
use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested;
use SixtyEightPublishers\MailingBundle\Application\Address;
use SixtyEightPublishers\MailingBundle\Application\Command\SendMailCommand;
use SixtyEightPublishers\MailingBundle\Application\Message;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetLocalizationPreferencesByEmailAddress;
use SixtyEightPublishers\UserBundle\ReadModel\View\LocalizationPreferences;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class SendPasswordChangeRequestedMail implements EventHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(PasswordChangeRequested $event): void
    {
        $localizationPreferences = $this->queryBus->dispatch(new GetLocalizationPreferencesByEmailAddress($event->getEmailAddress()->toNative()));
        $locale = $localizationPreferences instanceof LocalizationPreferences ? $localizationPreferences->locale->toNative() : null;
        $timezone = $localizationPreferences instanceof LocalizationPreferences ? $localizationPreferences->timezone : new DateTimeZone('UTC');
        $emailAddress = $event->getEmailAddress()->toNative();
        $passwordRequestId = $event->getAggregateId()->toNative();

        $message = Message::create('ForgotPasswordBundle/PasswordChangeRequest', $locale)
            ->withTo(new Address($emailAddress))
            ->withArguments([
                'emailAddress' => $emailAddress,
                'passwordRequestId' => $passwordRequestId,
                'expireAt' => $event->getExpiredAt()->setTimezone($timezone)->format('j.n.Y H:i'),
            ]);

        $this->commandBus->dispatch(new SendMailCommand($message), [
            new DispatchAfterCurrentBusStamp(),
        ]);
    }
}
