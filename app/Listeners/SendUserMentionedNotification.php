<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Filament\Clusters\Articles\Resources\ArticleReports\ArticleReportResource;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Kirschbaum\Commentions\Events\UserWasMentionedEvent;
use Kirschbaum\Commentions\Config;

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
            ])->sendToDatabase($event->user);
    }
}
