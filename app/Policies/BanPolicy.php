<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;

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
    /**
     * Before filter that is executed before any other policy check.
     *
     * This method checks if the user has the `Administrators` or `Developer` user type.
     * If so, it grants access to all actions, effectively bypassing the other policy checks.
     *
     * @param  User $user  The user to check.
     * @return bool        True if the user is an administrator or developer, false otherwise.
     */
    public function before(User $user): bool
    {
        return $user->user_type->in([UserTypes::Administrators, UserTypes::Developer]);
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
        return false;
    }

    /**
     * Determines whether the user can view a specific `Ban` model.
     *
     * This method always returns false, effectively denying access to view specific `Ban` models.
     * The `before` method handles access control for administrators and developers.
     *
     * @return bool Always false.
     */
    public function view(): bool
    {
        return false;
    }

    /**
     * Determines whether the user can update the `Ban` model.
     *
     * This method always returns false, effectively denying access to update `Ban` models.
     * The `before` method handles access control for administrators and developers.
     *
     * @return bool Always false.
     */
    public function update(): bool
    {
        return false;
    }

    /**
     * Determines whether the user can delete the `Ban` model.
     *
     * This method always returns false, effectively denying access to delete `Ban` models.
     * The `before` method handles access control for administrators and developers.
     *
     * @return bool Always false.
     */
    public function delete(): bool
    {
        return false;
    }
}
