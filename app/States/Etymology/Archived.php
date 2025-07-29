<?php

declare(strict_types=1);

namespace App\States\Etymology;

/**
 * The Archived class represents a concrete state within the etymology state machine, specifically signifying that an etymology entry is in an 'Archived' status.
 *
 * In this state, an etymology is considered inactive or no longer in active use, but its historical data is preserved.
 * This class defines the behavior for attempts to transition from the 'Archived' state to other possible states within the system.
 * A key characteristic of this implementation is that no direct transitions from the 'Archived' state to 'Under Review', 'Rejected', or 'Archived' (itself) are permitted.
 * This design implies that an archived etymology might require a specific "unarchive" or "restore" process to re-enter an active workflow, rather than direct transitions.
 *
 * @see EtymologyState  - The abstract base class for etymology states.
 * @see Etymology       - (If such a model exists and is managed by these states)
 *
 * @package App\States\Etymology
 */
final readonly class Archived extends EtymologyState
{
    /**
     * Attempts to transition the etymology from the 'Archived' state to 'Under Review'.
     *
     * This method is designed to prevent a direct transition from an 'Archived' state to an 'Under Review' state.
     * As per the current state machine definition, an archived etymology cannot directly re-enter the review process through this method.
     * Therefore, this method always returns `false`, indicating that the transition is not allowed from this specific state.
     *
     * @return bool Returns `false` because a direct transition from 'Archived' to 'Under Review' is not permitted.
     */
    public function transitionToUnderReview(): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Archived' state to 'Rejected'.
     *
     * This method tries to change the etymology's status from 'Archived' to 'Rejected', potentially including a reason for the rejection.
     * However, according to the current state logic for an archived etymology as defined in this class, a direct transition to 'Rejected' is not supported.
     * This ensures that archived content remains in its archived state unless a specific unarchiving process is initiated.
     * The method consistently returns `false` to indicate that this specific state change is disallowed.
     * The `$reason` parameter is accepted but ignored, as the transition is prevented.
     *
     * @param  string|null $reason  An optional reason for the rejection, which is ignored in this state.
     * @return bool                 Returns `false` because a direct transition from 'Archived' to 'Rejected' is not permitted.
     */
    public function transitionToRejected(?string $reason = null): bool
    {
        return false;
    }

    /**
     * Attempts to transition the etymology from the 'Archived' state to 'Archived'.
     *
     * This method represents an attempt to transition an etymology that is already in the 'Archived' state back to 'Archived'.
     * While this might seem redundant, it's part of the `EtymologyStateContract`.
     * As there is no actual state change required or permitted for an already archived item to re-enter its current state, this method consistently returns `false`.
     * This signifies that no operation was performed. The `$reason` parameter is accepted but ignored.
     *
     * @param  string|null $reason  An optional reason for the archiving, which is ignored in this state.
     * @return bool                 Returns `false` because the etymology is already in the 'Archived' state, and no transition is performed.
     */
    public function transitionToArchived(?string $reason = null): bool
    {
        return false;
    }
}
