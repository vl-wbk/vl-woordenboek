<?php

namespace App\Policies;

use App\Models\User;
use App\UserTypes;
use Illuminate\Database\Eloquent\Model;

/**
 * UserPOlicy enforces authorization rules for user management operations.
 *
 * This policy class controls access to user-related functionality within the Vlaams Woordenboek administration panel.
 * It implements strict access control that limits user management capabilities to administrators and developers, ensuring secure user administration.
 *
 * @package App\Policies
 */
final readonly class UserPolicy
{
    /**
     * Determines whether a user can view the user management interface.
     *
     * Access to the user listing and management interface is restricted to administrators and developers to maintain system security.
     * This ensures that only high-level users can access sensitive user information.
     *
     * @param  User $user  The user attempting to access the interface
     * @return bool        True if the user has viewing permission, false otherwise
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    /**
     * Determines whether a user can create new user account.
     *
     * The ability to create new users is limited to administrators and developers to maintain strict control over system access.
     * This centralized approach to user creation helps ensure proper role assignment and account security.
     *
     * @param  User $user  The user that is attempting to create another user account
     * @return bool        True if the user has the permission to create the user account, false otherwise
     */
    public function create(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    public function deactivate(User $user, User $model): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])
            && $user->isNot($model)
            && $model->isNotBanned();
    }

    public function reactivate(User $user, User $model): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])
            && $user->isNot($model)
            && $model->isBanned();
    }

    public function updateDeactivation(User $user, User $model): bool
    {

        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])
            && $user->isNot($model)
            && $model->isBanned();
    }
}
