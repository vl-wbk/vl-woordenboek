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
final readonly class BanPolicy
{
    public function before(User $user, string $ability): ?Response
    {
        if ($user->cannot('page_UserManagement')) {
            return Response::denyAsNotFound();
        }

        return null;
    }

    /**
     * Determines whether the user can view any `Ban` models.
     *
     * This method checks if the user has the `Administrators` or `Developer` user type.
     * If so, it grants access to view any `Ban` models.
     *
     * @param User $user The user to check.
     *
     * @return bool True if the user is an administrator or developer, false otherwise.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_ban');
    }

    /**
     * Determines whether the user can view a specific `Ban` model.
     *
     * This method always returns false, effectively denying access to view specific `Ban` models.
     * The `before` method handles access control for administrators and developers.
     *
     * @return bool Always false.
     */
    public function view(User $user, Ban $ban): bool
    {
        return $user->can('view_ban');
    }

    /**
     * Determines whether the user can update the `Ban` model.
     *
     * This method always returns false, effectively denying access to update `Ban` models.
     * The `before` method handles access control for administrators and developers.
     *
     * @return bool Always false.
     */
    public function update(User $user, Ban $ban): bool
    {
        return $user->can('update_ban');
    }

    /**
     * Determines whether the user can delete the `Ban` model.
     *
     * This method always returns false, effectively denying access to delete `Ban` models.
     * The `before` method handles access control for administrators and developers.
     *
     * @return bool Always false.
     */
    public function delete(User $user, Ban $ban): bool
    {
        return $user->can('delete_ban');
    }
}
