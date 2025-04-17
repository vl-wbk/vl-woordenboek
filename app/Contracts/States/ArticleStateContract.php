<?php

namespace App\Contracts\States;

/**
 * The ArticleStateContract interface defines a set of methods for managing the state transitions of an article.
 *
 * This interface standardizes how an article moves between key states such as "Editing", "Approved", "Released", "Archived", and "Suggestion".
 * By implementing this contract, state management becomes predictable and consistent across the application.
 * Each method should contain the logic required to carry out the specific transition and any accompanying side effects (e.g., logging, notifications, or validations).
 *
 * @package App\Contracts\States
 */
interface ArticleStateContract
{
    /**
     * Transitions the article into an "Editing" state.
     *
     * This method should implement the logic required to allow the article to be edited,
     * possibly triggering necessary events or validations to prepare the article for modifications.
     *
     * @param  string|null $reason Optional explanation for why the article is being moved to editing
     * @return bool
     */
    public function transitionToEditing(?string $reason = null): bool;

    /**
     * Transitions the article into an "Approved" state.
     *
     * Upon successful approval, this method should mark the article as ready for further processing
     * or publication, ensuring all approval criteria have been met.
     *
     * @return void
     */
    public function transitionToApproved(): void;

    /**
     * Transitions the article into a "Released" state.
     *
     * This method finalizes the publication process of the article, making it available to the end users.
     * Implementers might include additional logic such as logging or triggering notifications when the release occurs.
     *
     * @return bool
     */
    public function transitionToReleased(): bool;

    /**
     * Transitions the article into an "Archived" state.
     *
     * This method is responsible for archiving the article. It accepts an optional string parameter that
     * describes the reason for archiving, which can be used for auditing or user feedback purposes.
     *
     * @param string|null $archivingReason An optional reason explaining why the article is archived.
     * @return void
     */
    public function transitionToArchived(?string $archivingReason = null): void;

    /**
     * Transitions the article into a "Suggestion" state.
     *
     * This method should change the state of the article to indicate that it is now considered as a suggestion.
     * The method returns a boolean value to indicate whether the transition was performed successfully.
     *
     * @return bool True if the transition is successful; otherwise, false.
     */
    public function transitionToSuggestion(): bool;

    public function transitionToExternalData(): bool;
}
