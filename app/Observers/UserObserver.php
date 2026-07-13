<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\User;

/**
 * Handle model lifecycle events for the User model.
 *
 * This observer intercepts Eloquent operations on User records to manage cascading cleanups observation.
 * It ensures that when a user account is removed, related user-submitted content maintains its historical context
 * instead of causing orphan references or cascade errors.
 *
 * This class handles anonymization/preservation of community contributions during user deletion.
 * If you add new models that morph-link or belong to a user, determine whether those records should be
 * hard-deleted, soft-deleted, or anonymized here to ensure compliance with data privacy standards while
 * protecting the integrity of the project's crowdsourced history.
 *
 * @package App\Observers
 */
final readonly class UserObserver
{
    /**
     * Handle the user "deleting" event.
     *
     * Intercepts the deletion lifecycle phase to preserve authorship context on submitted suggestions.
     * Converts the relational dependency into a hardcoded string snapshot of the user's current name before the
     * underlying database record is permanently dropped.
     *
     * @param  User $user The user instance currently undergoing deletion.
     * @return void
     */
    public function deleting(User $user): void
    {
        $user->suggestions()->update(['contributor_name' => $user->name]);
    }
}
