<?php

declare(strict_types=1);

namespace App\States\Etymology;

/**
 * The Published class represents a concrete state within the etymology state machine, specifically signifying that an etymology entry is in a 'Published' status.
 *
 * In this state, an etymology is considered finalized and publicly accessible.
 * This class defines the behavior for attempts to transition from the 'Published' state to other possible states within the system.
 * A key characteristic of this implementation is that no direct transitions from the 'Published' state to 'Draft', 'UnderReview', 'or 'Rejected' states are permitted, as indicated
 * by all transition methods consistently returning `false`. This design implies that a published etymology may require a different process (e.g., creating a
 * new version or unpublishing through an external mechanism) to change its state.
 *
 * @see EtymologyState  - The abstract base class for etymology states.
 * @see Etymology       - (If such a model exists and is managed by these states)
 *
 * @package App\States\Etymology
 */
final readonly class Published extends EtymologyState
{
    /**
     * Attempts to transition the etymology from the 'Published' state to 'Draft'.
     *
     * This method is designed to prevent a direct transition from a 'Published' state to a 'Draft' state.
     * As per the current state machine definition, a published etymology cannot be directly reverted to a draft.
     * Therefore, this method always returns `false`, indicating that the transition is not allowed from this specific state.
     *
     * @return bool  Returns `false` because a direct transition from 'Published' to 'Draft' is not permitted.
     */
    public function transitionToDraft(): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Published' state to 'Under Review'.
     *
     * This method tries to change the etymology's status from 'Published' to 'Under Review'.
     * However, according to the current state logic for a published etymology, such a direct transition is not supported.
     * This ensures that published content remains stable unless an alternative process is initiated.
     * The method consistently returns `false` to indicate that this specific state change is disallowed.
     *
     * @return bool  Returns `false` because a direct transition from 'Published' to 'Under Review' is not permitted.
     */
    public function transitionToUnderReview(): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Published' state to 'Rejected'.
     *
     * This method is intended to handle an attempt to move a 'Published' etymology to a 'Rejected' state, potentially including a reason for the rejection.
     * In this specific state implementation, a direct rejection from the published state is not allowed, preserving the integrity of published content.
     * The `$reason` parameter is accepted but ignored, as the transition itself is prevented.
     * The method will always return `false`.
     *
     * @param string|null $reason  An optional reason for the rejection, which is ignored in this state.
     * @return bool                Returns `false` because a direct transition from 'Published' to 'Rejected' is not permitted.
     */
    public function transitionToRejected(?string $reason = null): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Published' state to 'Published'.
     *
     * This method represents an attempt to transition an etymology that is already in the 'Published' state back to 'Published'.
     * While this might seem redundant, it's part of the `PublicationStateContract`.
     * As there is no actual state change required or permitted for an already published item to re-enter its current state, this method consistently returns `false`.
     * This signifies that no operation was performed.
     *
     * @return bool  Returns `false` because the etymology is already in the 'Published' state, and no transition is performed.
     */
    public function transitionToPublished(): bool
    {
        return false;
    }
}
