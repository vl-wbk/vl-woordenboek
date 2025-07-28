<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Spatie\WelcomeNotification\WelcomeNotification as WelcomeNotificationBase;

final class WelcomeNotification extends WelcomeNotificationBase
{
    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage)
            ->subject('Er is een login aangemaakt vooru op het VL. woordenboek')
            ->greeting('Geachte!')
            ->line('Een administrator heeft voor u een login aangemaakt op het vlaams woordenboek. Met de knop hieronder kunt u uw wachtwoord instellen')
            ->action('Wachtwoord registreren', $this->showWelcomeFormUrl);
    }
}
