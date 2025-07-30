<?php

declare(strict_types=1);

namespace App\States\Etymology;

/**
 * State class for the "Rejected" status of an etymology.
 *
 * This class models the behavior of an etymology that has been rejected during the editorial process.
 * While in this state, the etymology cannot transition to "Under Review," "Rejected" again, or "Published."
 * Use this class to encapsulate logic and restrictions specific to rejected etymologies.
 *
 * Typical usage includes preventing further transitions or enabling workflows for handling rejected entries.
 * Extend this class to add more rejection-related behaviors as needed.
 *
 * @package App\States\Etymology
 */
final readonly class Rejected extends EtymologyState
{
    /**
     * Prevents transitioning to "Under Review" from the "Rejected" state.
     *
     * @return bool Always returns false, as transitioning to "Under Review" is not allowed.
     */
    public function transitionToUnderReview(): bool
    {
        return false;
    }

    /**
     * Prevents transitioning to "Rejected" again from the "Rejected" state.
     *
     * @param  string|null  $reason  Optional reason for the rejection attempt.
     * @return bool                  Always returns false, as the etymology is already rejected.
     */
    public function transitionToRejected(?string $reason = null): bool
    {
        return false;
    }

    /**
     * Prevents transitioning to "Published" from the "Rejected" state.
     *
     * @return bool Always returns false, as transitioning to "Published" is not allowed.
     */
    public function transitionToPublished(): bool
    {
        return false;
    }
}
