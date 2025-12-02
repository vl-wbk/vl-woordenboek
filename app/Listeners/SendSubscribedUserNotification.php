<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Features\SendCommentSubscriptionNotifications;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\Skip;
use Kirschbaum\Commentions\Events\UserIsSubscribedToCommentableEvent;
use Laravel\Pennant\Feature;

/**
 * SendSubscribedUserNotification
 *
 * The listener notifies a user via a queued Filament Notification when a new comment is posted on a resource the are subscribed to.
 * Execution is skipped if the 'SendCommentSubscriptionNotifications' feature flag is inactive.
 * The notification includes a direct link to the relevant article in the admin panel.
 *
 * @package App\Listeners
 */
final class SendSubscribedUserNotification implements ShouldQueue, ShouldBeEncrypted
{
    use InteractsWithQueue;

    /**
     * Get the middleware the job should pass through.
     *
     * This method ensures the listener's execution is conditional based on the state of a Laravel Pennant feature flag.
     * If 'SendCommentSubscriptionNotifications' is inactive, the queue job will be silently skipped.
     *
     * @return array<int, object> An array containing the queue middleware instances.
     */
    public function middleware(): array
    {
        return [
            Skip::when(
                Feature::inactive(feature: SendCommentSubscriptionNotifications::class)
            )
        ];
    }

    /**
     * Handle the UserIsSubscribedToCommentableEvent.
     *
     * This method sends a new Filament Notification to the subscribed user.
     * The notification includes the article's name and a direct link to the article's view page in the Filament administrative panel.
     *
     * @param  UserIsSubscribedToCommentableEvent $event The event instance containing the user and the new comment details.
     * @return void
     */
    public function handle(UserIsSubscribedToCommentableEvent $event): void
    {
        /** @var User $user */
        $user = $event->user;

        /** @var Article $article */
        $article = $event->comment->commentable;

        $user->notify(Notification::make()
            ->title('Reactie toegevoegd')
            ->icon(Heroicon::ChatBubbleLeftRight)
            ->body('Er is een nieuwe reactie toegevoegd in de opmerkingen van het artikel: ' . $article->word)
            ->actions([
                Action::make('view-article')
                    ->label('Bekijk artikel')
                    ->url(ArticleResource::getUrl('view', ['record' => $article]))
                    ->markAsRead()
            ])
            ->toDatabase());
    }
}
