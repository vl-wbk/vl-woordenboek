<?php

namespace App\Notifications\Reminders;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class PruneArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Collection $articles,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Herinnering: Woordenboekartikelen worden binnen 2 dagen definitief verwijderd')
            ->greeting('Beste ' . $notifiable->name . ',')
            ->line('De volgende woordenboekartikelen zijn eerder verwijderd en worden **binnen 2 dagen automatisch en permanent verwijderd**:')
            ->line('');

        foreach ($this->articles as $article) {
            $mail->line("**#{$article->id}** - {$article->word}, *(verwijderd op: {$article->deleted_at->format('d/m/Y')})*");
        }

        $mail->line('')
            ->line('Als deze artikelen behouden moeten blijven, gelieve tijdig actie te ondernemen.')
            ->line('Deze herinnering wordt slechts éénmalig verzonden.');

        return $mail;
    }
}
