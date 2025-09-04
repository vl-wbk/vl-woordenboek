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
			->subject(subject: __('mail-notifications.article-publication.subject', ['app' => config('app.name', 'laravel')]))
            ->greeting(greeting: __('mail-notifications.article-publication.greeting', ['name' => $this->getUserName()]))
			->line(line: __('mail-notifications.article-publication.first-line'))
			->line(line: __('mail-notifications.article-publication.second-line'))
            ->action(text: __('mail-notifications.article-publication.action'), url: $this->articleUrl());
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
