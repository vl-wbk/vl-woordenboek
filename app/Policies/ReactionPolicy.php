<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Reaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * ReactionPolicy enforces authorization rules for user reactions in the Flemisch dictionary.
 *
 * This policy class handles the permission logic for interacting with reactions (e.g., likes, kudos, or feedback)
 * attached to dictionary entries. It ensures that content moderation and interaction management
 * adhere to the established permission hierarchy.
 *
 * @package App\Policies
 */
final readonly class ReactionPolicy
{
    /**
     * Determinbes whether a user can update an existing reaction.
     *
     * Required permission: 'updàate:article'.
     * Currently, the ability to moderate or update reactions is tied to the broader article
     * modification rights to ensure consistent editorial oversight.
     *
     * @param User     $user     The authenticated user instance attempting the action.
     * @param Reaction $reaction The specific reaction record being updated.
     * @return Response          Grants access if the required permission is present.
     */
    public function update(User $user, Reaction $reaction): Response
    {
        return $user->can('update:article')
            ? Response::allow()
            : Response::deny(message: __('U hebt geen toestemming om deeze reactie te verwijderen.'));
    }
}
