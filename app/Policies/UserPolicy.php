<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * UserPolicy enforces authorization rules for user management operations.
 *
 * This policy class controls access to user-related capability within the Vlaams Woordenboek administration panel.
 * It implements strict access control that limits user management capabilities to administrators and developers, ensuring secure user administration.
 *
 * @link file://tests/Unit/Authorization/CategoryPolicyTest.php
 * @package App\Policies
 */
final class UserPolicy
{
    public static array $permissionPrefixes = [
        'viewAny', 'create', 'deactivate', 'deactivateUpdate', 'reactivate',
    ];

    public function before(User $user, string $ability): ?Response
    {
        if ($user->cannot('view:user-management-cluster')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    /**
     * Determines whether a user can view the user management interface.
     *
     * Access to the user listing and management interface is restricted to administrators and developers to maintain system security.
     * This ensures that only high-level users can access sensitive user information.
     *
     * @param  User $user  The user attempting to access the interface
     * @return Response    True if the user has viewing permission, false otherwise
     */
    public function viewAny(User $user): Response
    {
        if ($user->can('view-any:user')) {
			return Response::allow();
		}

		return Response::deny();
    }

    /**
     * Determines whether a user can create a new user account.
     *
     * The ability to create new users is limited to administrators and developers to maintain strict control over system access.
     * This centralized approach to user creation helps ensure proper role assignment and account security.
     *
     * @param  User $user  The user that's attempting to create another user account
     * @return Response    True if the user has the permission to create the user account, false otherwise
     */
    public function create(User $user): Response
    {
        if ($user->can('create:user')) {
			return Response::allow();
		}

		return Response::deny();
    }

    /**
     * Determines if a user can deactivate another user's account.
     *
     * This policy ensures that:
     * - Only administrators and developers can deactivate accounts
     * - Users can't deactivate their own account
     * - Only active (non-banned) accounts can be deactivated
     *
     * @param  User $user   The user attempting to perform the deactivation
     * @param  User $model  The user account to be deactivated
     * @return Response     True if the deactivation is allowed
     */
    public function deactivate(User $user, User $model): Response
    {
        if ($user->can('deactivate:user') && $user->isNot($model) && $model->isNotBanned()) {
			return Response::allow();
		}

		return Response::deny();
    }

    /**
     * Determines if a user can reactivate a deactivated account.
     *
     * This policy ensures that:
     * - Only administrators and developers can reactivate accounts
     * - Users can't reactivate their own account
     * - Only currently deactivated accounts can be reactivated
     *
     * @param  User $user   The user attempting to perform the reactivation
     * @param  User $model  The deactivated account to be restored
     * @return Response     True if the reactivation is allowed
     */
    public function reactivate(User $user, User $model): Response
    {
        if ($user->can('reactivate:user') && $user->isNot($model) && $model->isBanned()) {
			return Response::allow();
		}

		return Response::deny();
    }

    /**
     * Determines if a user can modify an existing deactivation.
     *
     * This policy ensures that:
     * - Only administrators and developers can modify deactivations
     * - Users can't modify their own deactivation
     * - Only currently deactivated accounts can have their deactivation modified
     *
     * Common modifications include updating the deactivation reason or duration.
     *
     * @param  User $user   The user attempting to modify the deactivation
     * @param  User $model  The deactivated account to be modified
     * @return Response     True if the modification is allowed
     */
    public function updateDeactivation(User $user, User $model): Response
    {
        if ($user->can('deactivate-update:user') && $user->isNot($model) && $model->isBanned()) {
			return Response::allow();
		}

		return Response::deny();
    }
}
