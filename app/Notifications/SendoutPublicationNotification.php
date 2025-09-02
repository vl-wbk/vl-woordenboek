<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Middleware\Skip;

final class SendoutPublicationNotification extends Notification implements ShouldQueue
{
    use Queueable;
	
    public function __construct(
		public Article $article,
	) {
		$this->afterCommit();
    }
	
	public function middleware(): array
	{
		return [
			Skip::unless($this->article->author && $this->article->notify_author)
		];
	}
	
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
	
    public function toMail(object $notifiable): MailMessage
    {
		// We only send the notification when the article is published the first time. This call marks that.
		$this->article->update(attributes: ['notify_author' => false]);
		
		// Build up the notification email
        return (new MailMessage)
			->subject(config('app.name', 'laravel') . ' - publicatie van een suggestie')
            ->greeting("Hey" . $this->getUserName() . ',')
			->line('We hebben goed nieuws! Je suggestie voor het Vlaams woordenboek is door ons team nagekeken en goedgekeurd. Bedankt voor je waardevolle bijdrage.')
			->line('De toevoeging is nu gepubliceerd en beschikbaar voor het brede publiek. Klik op de knop hieronder om het artikel te bekijken.')
            ->action('Bekijk de publicatie', $this->articleUrl());
    }
	
	private function getUserName(): ?string
	{
		return $this->article->author->name;
	}
	
	private function articleUrl(): string
	{
		return url('woordenboek-artikel/' . $this->article->id);
	}
}
