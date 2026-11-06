<?php

namespace App\Policies;

use App\Models\ModerationRule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final class ModerationRulePolicy
{
    /**
     * Registry of actions supported by this policy.
     * Used by the system to dynamically resolve permission strings and UI element visibility.
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = ['update', 'create', 'delete', 'deleteAny'];

    /**
     * Authorize the creation of a new moderation rules.
     * Requires the expliciet 'create:moderation-rule' permission.
     *
     * @param  User $user The identity attempting the action.
     * @return Response   Success if permitted, otherwise, a denied response.
     */
    public function create(User $user): Response
    {
        return $user->can('create:moderation-rule')
            ? Response::allow()
            : Response::deny(message: 'Je hebt geen machtiging om een gebruiker te deactiveren.');
    }

    /**
     * Authorize update to an existing Moderation Rule.
     * Validates that the user has administrative rights to modify active moderation logic.
     *
     * @param  User           $user             The dientify attempting the modification.
     * @param  ModerationRule $moderationRule   The specific rule instance being targeted.
     * @return Response
     */
    public function update(User $user, ModerationRule $moderationRule): Response
    {
        return $user->can('update:moderation-rule')
            ? Response::allow()
            : Response::deny(message: 'Je hebt geen machtiging om een deactivatie van een gebruiker aan te passen.');
    }

    /**
     * Authorize the removal of a single Moderation Rule.
     *
     * @param  User $user The identity attempting the deletion.
     * @return Response
     */
    public function delete(User $user): Response
    {
        return $user->can('delete:moderation-rule')
            ? Response::allow()
            : Response::deny(message: 'Je hebt geen machtiging om de deactivatie van een gebruiker ongedaan te maken.');
    }

    /**
     * Authorize bulk deletion of Moderation Rules.
     *
     * Note: This uses a hyphenated permission string 'delete-any'
     * to distinguish from single-resource deletion logic.
     *
     * @param  User $user The identity attempting the bulk action.
     * @return Response
     */
    public function deleteAny(User $user): Response
    {
        return $user->can('delete-any:moderation-rule')
            ? Response::allow()
            : Response::deny(message: 'Je hebt geen machtiging om deactivaties ongedaan te maken.');
    }
}
