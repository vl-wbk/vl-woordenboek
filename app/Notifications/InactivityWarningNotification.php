<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InactivityWarningNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Je account is gemarkeerd voor verwijdering')
            ->greeting("Hey {$notifiable->name},")
            ->line('We merken dat het al 5 maanden geleden is dat je bent aangemeld voor het Vlaams Woordenboek.')
            ->line('Om niet onnodig gegevens van gebruikers te bewaren hebben we je account gemarkeerd voor verwijderen. En zal verwijderd worden op ' . now()->addMonths(6)->format('d-m-Y') . '.')
            ->line('Om de toegang tot je account te behouden vragen we je om aan te melden op het Vlaams Woordenboek om de automatische verwijdering te stoppen.');
    }
}
