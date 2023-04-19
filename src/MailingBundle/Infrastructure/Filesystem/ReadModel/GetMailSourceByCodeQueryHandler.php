<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Infrastructure\Filesystem\ReadModel;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MessageBody;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Subject;
use SixtyEightPublishers\MailingBundle\Infrastructure\Filesystem\Locator\MailSourceLocatorInterface;
use SixtyEightPublishers\MailingBundle\ReadModel\Query\GetMailSourceByCodeQuery;
use SixtyEightPublishers\MailingBundle\ReadModel\View\MailSource;
use SixtyEightPublishers\MailingBundle\ReadModel\View\SourceType;

final class GetMailSourceByCodeQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly MailSourceLocatorInterface $mailSourceLocator,
    ) {}

    public function __invoke(GetMailSourceByCodeQuery $query): ?MailSource
    {
        $messageBodyFile = $this->mailSourceLocator->locale($query->code, $query->locale);

        if (null === $messageBodyFile) {
            return null;
        }

        $subjectFile = $this->mailSourceLocator->locale($query->code, $query->locale, '.subject');
        $layoutFile = $this->mailSourceLocator->locale('@layout', $query->locale);

        return new MailSource(
            SourceType::FILE_PATH,
            null !== $subjectFile ? Subject::fromSafeNative($subjectFile) : null,
            MessageBody::fromSafeNative($messageBodyFile),
            null !== $layoutFile ? MessageBody::fromSafeNative($layoutFile) : null,
            Locale::fromSafeNative($query->locale),
        );
    }
}
