<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Cog\Laravel\Ban\Models\Ban;
use Illuminate\Auth\Access\Response;

/**
 * Class BanPolicy
 *
 * This class defines the authorization policy for the 'ban' model.
 * It Determines which users are allowed to perform certain actions on 'ban' objects, such as viewing, updating, or deleting them.
 *
 * The policy grants blanket access to `Administrators` and `Developer` user types for all actions.
 * Other user types are implicitly denied access.
 *
 * @package App\Policies
 */
final class BanPolicy
{
    /**
     * Lists the permission action prefixes defined in this policy.
     * These prefixes combine with the resource name (e.g., ':ban') for authorization checks.
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = [
        'viewAny', 'view', 'update', 'delete',
    ];

    /**
     * Determines whether the user can view any `Ban` models.
     *
     * This method checks if the user has the `Administrators` or `Developer` user type.
     * If so, it grants access to view any `Ban` models.
     *
     * @param  User $user 	The user to check.
     */
    public function viewAny(User $user): Response
    {
        if ($user->can('view-any:ban')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether the user can view a specific `Ban` model.
     *
     * This method always returns false, effectively denying access to view specific `Ban` models.
     * The `before` method handles access control for administrators and developers.
     */
    public function view(User $user, Ban $ban): Response
    {
        if ($user->can('view:ban')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether the user can update the `Ban` model.
     *
     * This method always returns false, effectively denying access to update `Ban` models.
     * The `before` method handles access control for administrators and developers.
     */
    public function update(User $user, Ban $ban): Response
    {
        if ($user->can('update:ban')) {
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determines whether the user can delete the `Ban` model.
     *
     * This method always returns false, effectively denying access to delete `Ban` models.
     * The `before` method handles access control for administrators and developers.
     */
    public function delete(User $user, Ban $ban): Response
    {
        if ($user->can('delete:ban')) {
            return Response::allow();
        }

        return Response::deny();
    }
}
