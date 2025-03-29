<?php

declare(strict_types=1);

namespace App\States\Articles;

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
}
