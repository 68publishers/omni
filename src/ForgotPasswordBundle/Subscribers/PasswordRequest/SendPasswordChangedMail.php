<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Subscribers\PasswordRequest;

use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted;
use SixtyEightPublishers\MailingBundle\Application\Address;
use SixtyEightPublishers\MailingBundle\Application\Command\SendMailCommand;
use SixtyEightPublishers\MailingBundle\Application\Message;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetLocalizationPreferencesByEmailAddress;
use SixtyEightPublishers\UserBundle\ReadModel\View\LocalizationPreferences;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class SendPasswordChangedMail implements EventHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(PasswordChangeCompleted $event): void
    {
        $localizationPreferences = $this->queryBus->dispatch(new GetLocalizationPreferencesByEmailAddress($event->getEmailAddress()->toNative()));
        $locale = $localizationPreferences instanceof LocalizationPreferences ? $localizationPreferences->locale->toNative() : null;
        $emailAddress = $event->getEmailAddress()->toNative();

        $message = Message::create('ForgotPasswordBundle/PasswordChanged', $locale)
            ->withTo(new Address($emailAddress))
            ->withArguments([
                'emailAddress' => $emailAddress,
            ]);

        $this->commandBus->dispatch(new SendMailCommand($message), [
            new DispatchAfterCurrentBusStamp(),
        ]);
    }
}
