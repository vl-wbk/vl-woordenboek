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
            ->subject("Welkom als vrijwilliger bij het Vlaams Woordenboek")
            ->line(
                "We hebben je aanmelding als vrijwilliger bekijken en goedgekeurd. Vanaf nu kun je bijdragen in het redactieteam van het Vlaams Woordenboek.",
            )
            ->line(
                "Hou je nog even in! Klik voor je eraan begint op de knop hieronder en lees de richtlijnen eerst. Dan weet je meteen wat de bedoeling is.",
            )
            ->action(
                "richtlijnen",
                "https://docs.google.com/document/d/1YvDcytvR7kqjBWXrUWD_taqIPSsqdpvo/edit?usp=sharing&rtpof=true&sd=true",
            )
            ->line("Dikke merci voor je hulp!");
    }
}
