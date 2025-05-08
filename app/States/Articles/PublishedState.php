<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use Illuminate\Support\Facades\DB;

/**
 * Represents the "Published" state of a dictionary article.
 *
 * This state indicates that the article is publicly visible and accessible to users.
 * Articles in this state are considered finalized and ready for public consumption.
 * However, they can still transition to other states, such as "Archived," if needed.
 *
 * The `PublishedState` class is part of the state pattern implementation for managing the lifecycle of articles.
 * It encapsulates the behavior and transitions specific to the "Published" state, ensuring that state-specific logic is centralized and easy to maintain.
 *
 * @package App\States\Articles
 */
final class PublishedState extends ArticleState
{
    /**
     * Transitions the article from the "Published" state to the "Archived" state.
     *
     * This method is used to archive an article that is currently published. It delegates the archiving logic to the `archive` method of the `Article` model,
     * ensuring that the archiving reason, timestamp, and other related data are properly recorded.
     *
     * Archiving an article removes it from public visibility while retaining it in the system for historical reference or potential future restoration.
     *
     * @param  string|null $archivingReason The reason for archiving the article (optional).
     * @return void
     */
    public function transitionToArchived(?string $archivingReason = null): void
    {
        $this->article->archive($archivingReason);
    }

    /**
     * Transitions the article from the "published" state back to the "draft" state.
     *
     * This method is used to unpublish an article, effectively reverting it to a work in progress state.
     * It performs the following actions within a database transaction to ensure data consistency:
     *
     * 1.  Updates the article's `state` attribute to `ArticleStates::Draft` and sets the `published_at` attribute to null.
     * 2.  Dissociates the publisher (user who published the article) from the article.
     * 3.  Attaches a note to the article recording the reason for unpublishing.
     *
     * @param  string|null $reason  The reason for transitioning the article back to the "Draft" state (optional). This reason will be recorded in the attached note.
     * @return bool                 True if the transition was successful, false otherwise.
     */
    public function transitionToEditing(?string $reason = null): bool
    {
        return DB::transaction(function () use ($reason): bool {
            $this->article->update(attributes: ['state' => ArticleStates::Draft, 'published_at' => null]);
            $this->article->publisher()->dissociate();

            $this->article->attachNote(title: 'Ongedaan maken van de publicatie voor het artikel', note: $reason);

            return true;
        });
    }
}
