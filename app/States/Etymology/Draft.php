<?php

declare(strict_types=1);

namespace App\States\Etymology;

final readonly class Draft extends EtymologyState
{
    /**
     * Attempts to transition the etymology from the 'Draft' state to 'Draft'.
     *
     * This method is designed to prevent a redundant self-transition or to enforce
     * a specific flow where an explicit action is required to move a draft to
     * another state, even if that state is conceptually similar (like re-saving
     * a draft). As per the current state machine definition for this concrete
     * 'Draft' state, a direct transition back to itself is not permitted through
     * this method. Therefore, this method always returns `false`, indicating that
     * no state change operation was performed.
     *
     * @return bool Returns `false` because a direct self-transition from 'Draft' to 'Draft' is not permitted.
     */
    public function transitionToDraft(): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Draft' state to 'Rejected'.
     *
     * This method tries to change the etymology's status from 'Draft' to 'Rejected', potentially including a reason for the rejection.
     * However, according to the current state logic for a draft etymology as defined in this class, a direct transition to 'Rejected' is not supported.
     * This design might imply that a draft must first enter an 'Under Review' state before it can be rejected.
     * The method consistently returns `false` to indicate that this specific state change is disallowed. The `$reason` parameter is accepted but ignored, as the transition is prevented.
     *
     * @param  string|null $reason  An optional reason for the rejection, which is ignored in this state.
     * @return bool                 Returns `false` because a direct transition from 'Draft' to 'Rejected' is not permitted.
     */
    public function transitionToRejected(?string $reason = null): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Draft' state to 'Published'.
     *
     * This method is intended to handle an attempt to move a 'Draft' etymology directly to a 'Published' state.
     * In this specific state implementation, a direct publication from the draft state is not allowed, possibly requiring an intermediate 'Under Review' state
     * or another workflow step. Therefore, this method always returns `false` to signify that this direct transition is not permitted from the current 'Draft' state.
     *
     * @return bool  Returns `false` because a direct transition from 'Draft' to 'Published' is not permitted.
     */
    public function transitionToPublished(): bool
    {
        return false;
    }
}
