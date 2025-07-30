<?php

declare(strict_types=1);

namespace App\States\Etymology;

/**
 * State class for the "Under Review" status of an etymology.
 *
 * This class models the behavior of an etymology that is currently under editorial review.
 * While in this state, the etymology cannot transition to "Under Review" again, and any such attempt will fail.
 * Use this class to encapsulate logic and transitions specific to the review process.
 *
 * Typical usage includes restricting certain actions or enabling review-specific workflows.
 * Extend this class to add more review-related behaviors as needed.
 *
 * @package App\States\Etymology
 */
final readonly class UnderReview extends EtymologyState
{
    /**
     * {@inheritDoc}
     */
    public function transitionToUnderReview(): bool
    {
        return false;
    }
}
