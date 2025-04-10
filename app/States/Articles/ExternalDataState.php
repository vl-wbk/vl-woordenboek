<?php

declare(strict_types=1);

namespace App\States\Articles;

use App\Enums\ArticleStates;
use Illuminate\Support\Facades\DB;

/**
 * ExternalDataState represents the state of an article that has been sourced from external data.
 *
 * In this state, the article's data is considered to originate outside of the standard creation workflow.
 * This class provides the functionality to transition an article from its external data state into an editable state.
 *
 * When such a transition is triggered, the current user is set as the article’s editor and the article's state is updated to indicate that it is now a draft ready for further editing.
 * The entire process is executed within a database transaction to ensure that the user assignment and state update occur as a single atomic operation.
 *
 * @package App\States\Articles
 */
final class ExternalDataState extends ArticleState
{
    /**
     * Transitions the article from the external data state to an editable draft state.
     *
     * When invoked, this method updates the article by assigning the current user as its editor and changing its state to ArticleStates::Draft.
     * The process is wrapped in a database transaction to guarantee that both actions occur atomically.
     * An optional reason may be provided to document why the transition is occurring, though it is not used within the method.
     *
     * @param   string|null  $reason  An optional explanation for the transition.
     * @return  bool                  True if the transition was completed successfully, false otherwise.
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
