<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;

/**
 * ArchivedState represents the archived status of a dictionary article.
 *
 * This state indicates that an article has been temporarily removed from public view but remains in the system for historical reference or potential future restoration.
 * Articles in this state can be transitioned back to published status if needed, providing flexibility in content management while maintaining data integrity.
 *
 * @package App\States\Articles
 */
final class ArchivedState extends ArticleState
{
    /**
     * Transitions an archived article back to published status.
     *
     * This method enables content restoration by changing the article's state from archived to published.
     * This transition might be used when previously archived content becomes relevant again or when an article was archived by mistake.
     * The change is immediately persisted to the database through the article model.
     */
    public function transitionToReleased(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Published]);
    }
}
