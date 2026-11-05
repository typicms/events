<?php

declare(strict_types=1);

namespace TypiCMS\Modules\Events\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use TypiCMS\Modules\Events\Models\Event;
use TypiCMS\Modules\Events\Models\Registration;

class NewRegistrationToAnEvent extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Event $event,
        private readonly Registration $registration,
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
