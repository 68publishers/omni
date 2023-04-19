<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\DeletableAggregateRootTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\MailingBundle\Domain\Command\CreateMailCommand;
use SixtyEightPublishers\MailingBundle\Domain\Event\MailCodeChanged;
use SixtyEightPublishers\MailingBundle\Domain\Event\MailCreated;
use SixtyEightPublishers\MailingBundle\Domain\Event\MailMessageBodyChanged;
use SixtyEightPublishers\MailingBundle\Domain\Event\MailSubjectChanged;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Code;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MessageBody;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Subject;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Translation;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Translations;
use function assert;

class Mail implements AggregateRootInterface
{
    use DeletableAggregateRootTrait;

    private MailId $id;

    private Code $code;

    /** @var Collection<int, MailTranslation> */
    private Collection $translations;

    public static function create(
        CreateMailCommand $command,
        ?CodeGuardInterface $codeGuard = null,
    ): static {
        $mail = new static(); // @phpstan-ignore-line

        $mailId = null !== $command->mailId ? MailId::fromNative($command->mailId) : MailId::new();
        $code = Code::fromNative($command->code);
        $translations = Translations::fromNative($command->getTranslations());

        $codeGuard && $codeGuard($mailId, $code);

        $mail->recordThat(MailCreated::create(
            $mailId,
            $code,
            $translations,
        ));

        return $mail;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id->toAggregateId();
    }

    public function changeCode(string $code, ?CodeGuardInterface $codeGuard = null): void
    {
        $code = Code::fromNative($code);

        if (!$this->code->equals($code)) {
            $codeGuard && $codeGuard($this->id, $code);
            $this->recordThat(MailCodeChanged::create($this->id, $code));
        }
    }

    public function changeSubject(string $subject, string $locale): void
    {
        $subject = Subject::fromNative($subject);
        $locale = Locale::fromNative($locale);
        $translation = $this->filterTranslation($locale);

        if (null === $translation || !$translation->getSubject()->equals($subject)) {
            $this->recordThat(MailSubjectChanged::create($this->id, $subject, $locale));
        }
    }

    public function changeMessageBody(string $messageBody, string $locale): void
    {
        $messageBody = MessageBody::fromNative($messageBody);
        $locale = Locale::fromNative($locale);
        $translation = $this->filterTranslation($locale);

        if (null === $translation || !$translation->getMessageBody()->equals($messageBody)) {
            $this->recordThat(MailMessageBodyChanged::create($this->id, $messageBody, $locale));
        }
    }

    protected function whenMailCreated(MailCreated $event): void
    {
        $this->id = MailId::fromAggregateId($event->getAggregateId());
        $this->code = $event->getCode();
        $this->translations = new ArrayCollection();

        foreach ($event->getTranslations()->all() as $translation) {
            assert($translation instanceof Translation);

            $this->translations->add(new MailTranslation($this, $translation));
        }
    }

    protected function whenMailCodeChanged(MailCodeChanged $event): void
    {
        $this->code = $event->getCode();
    }

    protected function whenMailSubjectChanged(MailSubjectChanged $event): void
    {
        $translation = $this->filterTranslation($event->getLocale()) ?? new MailTranslation($this, new Translation($event->getLocale(), $event->getSubject(), MessageBody::fromNative('')));
        $translation->setSubject($event->getSubject());

        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
        }
    }

    protected function whenMailMessageBodyChanged(MailMessageBodyChanged $event): void
    {
        $translation = $this->filterTranslation($event->getLocale()) ?? new MailTranslation($this, new Translation($event->getLocale(), Subject::fromNative(''), $event->getMessageBody()));
        $translation->setMessageBody($event->getMessageBody());

        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
        }
    }

    private function filterTranslation(Locale $locale): ?MailTranslation
    {
        return $this->translations->findFirst(static fn (int $key, MailTranslation $mailTranslation): bool => $mailTranslation->getLocale()->equals($locale));
    }
}
