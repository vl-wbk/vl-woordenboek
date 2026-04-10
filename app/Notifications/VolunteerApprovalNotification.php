<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VolunteerApprovalNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ["mail"];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return new MailMessage()
            ->line(
                "We hebben je aanmelding als vrijwilliger bekijken en goedgekeured. Vanaf nu kun je bijdragen in het redactie team van het Vlaams Woordenboek.",
            )
            ->line(
                "Alvorens je begint met bijdragen vragen we je om de richtlijnen te raadplegen die je kunt raadplegen doormiddel van de onderstaande knoppen.",
            )
            ->action(
                "richtlijn: bewerking van suggesties",
                "https://docs.google.com/document/d/1YvDcytvR7kqjBWXrUWD_taqIPSsqdpvo/edit?usp=sharing&rtpof=true&sd=true",
            )
            ->line("Bedankt alvast voor je inzet");
    }
}
