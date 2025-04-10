<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use Illuminate\Support\Facades\DB;

/**
 * DraftState handles the state transitions for articles in the Draft state.
 *
 * When an article is in the Draft state, it can be transitioned to an Approval state for further review, or reverted to a New (suggestion) state if changes are needed.
 * This class implements those transitions by overriding the methods inherited from the ArticleState base class.
 *
 * Key behaviors implemented in this class:
 * - transitionToApproved(): Changes the article from Draft to Approval state.
 * - transitionToSuggestion(): Reverts the article to a New state and clears the assigned editor, all within a database transaction to ensure data consistency.
 *
 * This design allows for clear separation of behaviors based on the article's current state, making it easier for developers to understand and extend state-specific logic.
 *
 * @package App\States\Articles
 */
final class DraftState extends ArticleState
{
    /**
     * Transitions an article from Draft to Approval state.
     * This method updates the article's state to ArticleStates::Approval.
     *
     * @return void
     */
    public function transitionToApproved(): void
    {
        $this->article->update(attributes: ['state' => ArticleStates::Approval]);
    }

    public function transitionToExternalData(): bool
    {
        return DB::transaction(function (): bool {
            return $this->article->update(attributes: [
                'state' => ArticleStates::ExternalData, 'editor_id' => null
            ]);
        });
    }

    /**
     * Transitions an article from Draft to New (suggestion) state.
     *
     * This method performs the state change within a database transaction to ensure atomicity.
     * It reverts the article's state to ArticleStates::New and clears the assigned editor.
     *
     * @return bool True if the update succeeds, false otherwise.
     */
    public function transitionToSuggestion(): bool
    {
        return DB::transaction(function (): bool {
            return $this->article->update(attributes: [
                'state' => ArticleStates::New, 'editor_id' => null
            ]);
        });
    }
}
