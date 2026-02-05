<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use App\Notifications\SendoutPublicationNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Approval represents an article awaiting editorial review in the Vlaams Woordenboek.
 *
 * This state indicates that the article has been submitted for review and requires editorial approval before publication.
 * The state provides multiple transition paths to support various editorial decisions: returning to draft for further editing, approving for publication, or archiving if deemed unsuitable.
 *
 * @package App\States\Articles
 */
final class Approval extends ArticleState
{
    public function transitionToRejectedPublication(array $feedback): bool
    {
        return DB::transaction(function () use ($feedback): bool {
            $this->article->update(attributes: ['state' => ArticleStates::RejectedPublication, 'feedback' => $feedback, 'published_at' => null]);
            return true;
        });
    }

    /**
     * Approves the article and transitions it to published status.
     *
     * This transition occurs when an editor determines the article meets all quality standards and is ready for public viewing.
     * The article becomes visible to all users once published.
     */
    public function transitionToReleased(Carbon $publicationDate): bool
    {
        return DB::transaction(function () use ($publicationDate): bool {
            $this->article
                ->setCurrentUserAsPublisher()
                ->update(attributes: ['state' => ArticleStates::Published, 'feedback' => null, 'published_at' => $publicationDate]);

            $this->article->author->notify(new SendoutPublicationNotification($this->article));

            return true;
        });
    }

    /**
     * Archives the article, removing it from the active review process.
     *
     * This transition is used when an editor decides the article should not be published but should be retained for reference.
     * Archived articles can be restored later if circumstances change.
     */
    public function transitionToArchived(?string $archivingReason = null): bool
    {
        return $this->article->archive($archivingReason);
    }
}
