<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\Middleware\Skip;

/**
 * Sends a publication notification to the article's author.
 *
 * This notification is queued and will be only sent if the article's author is set and the 'notify_author' author is set, and the `notify_author` flag on the article is true.
 * After the notification is sent, the `notify_author` flag is set to false to prevent future notifications for the same publication.
 *
 * @package App\Notifications
 */
final class SendoutPublicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Creates a new notification instance.
     * The `afterCommit()` method ensures the notification is dispatched only after the database transaction has successfully completed.
     *
     * @param Article $article The article being published.
     */
    public function __construct(
        public Article $article,
    ) {
        $this->afterCommit();
    }

    /**
     * Get the notification's middleware.
     * The middleware prevents the notification from being queued and processed if the conditions aren't met, ensuring the notification is only sent when intended.
     *
     * @return array<int, \Illuminate\Queue\Middleware\Skip> The middleware array.
     */
    public function middleware(): array
    {
        return [
            Skip::unless($this->article->author && $this->article->notify_author),
        ];
    }

    /**
     * Get the notification channels.
     *
     * @param  object $notifiable The notifiable entity (e.g., the user).
     * @return array<int, string> The channels the notification should be sent through.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     * This method updates the article's `notify_author` attribute to `false` to ensure the notification is sent only once per publication.
     *
     * @param  object $notifiable The notifiable entity.
     * @return MailMessage The mail message instance.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // We only send the notification when the article is published the first time. This call marks that.
        $this->article->update(attributes: ['notify_author' => false]);

        // Build up the notification email
        return (new MailMessage())
            ->subject(subject: __('mail-notifications.article-publication.subject', ['app' => config('app.name', 'laravel')]))
            ->greeting(greeting: __('mail-notifications.article-publication.greeting', ['name' => $this->getUserName()]))
            ->line(line: __('mail-notifications.article-publication.first-line'))
            ->line(line: __('mail-notifications.article-publication.second-line'))
            ->action(text: __('mail-notifications.article-publication.action'), url: $this->articleUrl());
    }

    /**
     * Retrieves the name of the article's author.
     *
     * @return string|null The author's name, or `null` if not available.
     */
    private function getUserName(): ?string
    {
        return $this->article->author->name;
    }

    /**
     * Generates the URL to the article.
     *
     * @return string The full URL to the published article.
     */
    private function articleUrl(): string
    {
        return url('woordenboek-artikel/' . $this->article->id);
    }
}
