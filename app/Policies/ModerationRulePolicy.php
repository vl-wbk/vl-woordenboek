<?php

namespace App\Policies;

use App\Models\ModerationRule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @todo Document this policy method.
 */
final class ModerationRulePolicy
{
    /**
     * @var list<string>
     */
    public static array $permissionPrefixes = ['update', 'create', 'delete', 'deleteAny'];

    public function create(User $user): Response
    {
        return $user->can('create:moderation-rule')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, ModerationRule $moderationRule): Response
    {
        return $user->can('update:moderation-rule')
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user): Response
    {
        return $user->can('delete:moderation-rule')
            ? Response::allow()
            : Response::deny();
    }

    public function deleteAny(User $user): Response
    {
        return $user->can('delete-any:moderation-rule')
            ? Response::allow()
            : Response::deny();
    }
}
