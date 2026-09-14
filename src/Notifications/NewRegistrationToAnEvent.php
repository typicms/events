<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewRegistrationToAnEvent extends Notification
{
    use Queueable;

    public function __construct(
        private readonly mixed $event,
        private readonly mixed $registration,
    ) {}

    /** @return string[] */
    public function via(): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return new MailMessage()
            ->subject('['.websiteTitle().'] '.__('New registration to :event', ['event' => $this->event->title]))
            ->markdown('mails::events.new-registration-to-event', [
                'event' => $this->event,
                'registration' => $this->registration,
            ]);
    }
}
