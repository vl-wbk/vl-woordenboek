<?php

declare(strict_types=1);

namespace App\Notifications\Reminders;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

/**
 * Class PruneArticleNotification
 *
 * The PruneArticleNotification class handles email notifications about articles that are scheduled for permanent deletion.
 * This notification serves as a final reminder to administrators and developers, giving them an opportunity to review and potentially restore articles before they are permanently removed from the system.
 *
 * This notification is queued to prevent blocking the main application thread when sending emails to multiple recipients.
 * It provides detailed information about each article scheduled for deletion, including its ID, title, and the date it was initially deleted.
 *
 * @package App\Notifications\Reminders
 */
final class PruneArticleNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Creates a new notification instance.
     *
     * This constructor accepts a collection of articles that are scheduled for permanent deletion.
     * These articles will be listed in the notification email to help recipients identify which content needs their attention.
     *
     * @param Collection<int, \App\Models\Article> $articles A collection of articles scheduled for deletion
     */
    public function __construct(
        protected Collection $articles,
    ) {}

    /**
     * Specifies the notification delivery channels.
     * This notification uses the email channel exclusively as it requires detailed formatting and contains important information that should be readily accessible and reviewable by the recipients.
     *
     * @return array<int, string> An array containing the delivery channels
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Builds the email message structure for this notification.
     * The method creates a detailed email that includes a clear subject line indicating urgency, a personalized greeting using the recipient's name, and a comprehensive overview of all articles scheduled for deletion.
     * Each article entry shows its ID, title, and deletion date.
     * The message concludes with a clear call to action for preserving important content and a note about the one-time nature of this notification.
     *
     * The email content uses Markdown formatting to enhance readability and highlight critical information such as article IDs and dates.
     *
     * @param  User $notifiable  The recipient who will receive this notification
     * @return MailMessage       The fully configured email message
     */
    public function toMail(User $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Herinnering: Woordenboekartikelen worden binnen 2 dagen definitief verwijderd')
            ->greeting('Beste ' . $notifiable->name . ',')
            ->line('De volgende woordenboekartikelen werden voorlopig verwijderd. **Binnen 2 dagen worden ze automatisch en permanent verwijderd**:')
            ->line('');

        foreach ($this->articles as $article) {
            $mail->line("**#{$article->id}** - {$article->word}, *(verwijderd op: {$article->deleted_at->format('d/m/Y')})*");
        }

        $mail->line('')
            ->line("Als dit artikel of deze artikelen toch behouden moeten blijven, moet je nu actie ondernemen door ze bijvoorbeeld in 'kladversie' te zetten.")
            ->line('Je krijgt deze herinnering maar één keer. ');

        return $mail;
    }
}
