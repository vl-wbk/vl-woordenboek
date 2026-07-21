<?php

declare(strict_types=1);

namespace App\Listeners;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Kirschbaum\Commentions\Events\UserWasMentionedEvent;

final class SendUserMentionedNotification implements ShouldQueue
{
    use Queueable;

    public function handle(UserWasMentionedEvent $event): void
    {
        Notification::make('user-mentioned')
            ->icon(Heroicon::ChatBubbleLeftRight)
            ->title('Vermelding in opmerkingen')
            ->body('Je bent vermeld in een opmerking')
            ->actions([
                Action::make('read-notification')
                    ->label('Bekijk artikel')
                    ->url(route('filament.admin.articles.resources.articles.view', $event->comment->commentable) . '#comment-' . $event->comment->getId())
                    ->markAsRead()
            ])
            /** @phpstan-ignore-next-line  */
            ->sendToDatabase($event->user);
    }
}
