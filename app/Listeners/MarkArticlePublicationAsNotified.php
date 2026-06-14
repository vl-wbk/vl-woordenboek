<?php

namespace App\Listeners;

use App\Notifications\SendoutPublicationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Events\NotificationSent;

final readonly class MarkArticlePublicationAsNotified implements ShouldQueue
{
    public function handle(NotificationSent $event): void
    {
        if ($event->notification instanceof SendoutPublicationNotification) {
            $event->notification->article->update(['notify_author' => false]);
        }
    }
}
