<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use Illuminate\Support\Facades\DB;

/**
 * The NewState class represents the initial state of an article when it is first created.
 * It encapsulates the behavior specific to a freshly created article, particularly how it can transition to a state where editing is permitted.
 * This state class builds on the common functionality provided by its parent, ArticleState, to ensure that the article's state is managed consistently.
 *
 * The transitionToEditing method defined here allows an article in the New state to move into an editable state by performing a database transaction.
 * Within this transaction, the current user becomes associated as the article's editor, and the article's state is updated to indicate that work on it has started.
 * This approach of bundling related state changes within a transaction helps maintain data integrity by ensuring that all required changes occur together.
 *
 * @package App\States\Articles
 */
final class NewState extends ArticleState
{
    /**
     * Transitions the article from the New state to a state that permits editing.
     *
     * This method wraps the entire transition process in a database transaction to guarantee that both setting the current user as the editor and updating the article's state occur atomically.
     * The method accepts an optional reason for the transition, although this parameter is not used within the logic.
     * Instead, its primary function is to update the article by registering the user responsible for editing and marking the article as moved into Draft state, effectively preparing it for further modifications.
     *
     * @param  string|null  $reason  An optional explanation for the state transition, currently not used in the logic.
     * @return bool                 Returns true if both the editor is set and the state update to Draft is successful, otherwise false.
     */
    public function transitionToEditing(?string $reason = null): bool
    {
        return DB::transaction(function (): bool {
            return $this->article
                ->setCurrentUserAsEditor()
                ->update(['state' => ArticleStates::Draft]);
        });
    }
}
