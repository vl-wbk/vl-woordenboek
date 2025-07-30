<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;

/**
 * The DisclaimerPolicy class defines the authorization rules for interacting with `Disclaimer` models within the application.
 * It dictates which user roles have permission to perform various actions such as viewing, creating, updating, and deleting disclaimers.
 *
 * This policy ensures that only users with specific administrative or editorial roles can manage disclaimers, thereby maintaining the integrity and control over critical legal or informational texts displayed in the application.
 * It leverages the `UserTypes` enum for clear and type-safe role-based access control.
 *
 * @see User        - The User model for which permissions are being checked.
 * @see UserTypes   - The enum defining different user roles.
 * @see Disclaimer  - (Implicitly, as this policy governs its access)
 *
 * @package App\Policies
 */
final readonly class DisclaimerPolicy
{
    /**
     * Determine whether the user can view any disclaimers.
     *
     * This method grants permission to view a list of all disclaimers.
     * Access is restricted to users whose `user_type` is either `EditorInChief`, `Administrator`, or `Developer`.
     * This ensures that only high-level personnel can oversee the disclaimer content.
     *
     * @param  User  $user  The user attempting to view disclaimers.
     * @return bool         Returns `true` if the user has the required user type, otherwise `false`.
     */
    public function viewAny(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::EditorInChief, UserTypes::Administrators, UserTypes::Developer]);
    }

    /**
     * Determine whether the user can create new disclaimers.
     *
     * This method controls the ability to add new disclaimer entries to the database.
     * Creation is limited to users with `Administrator` or `Developer` user types, reflecting a higher level of permission required for content generation.
     *
     * @param  User  $user  The user attempting to create a disclaimer.
     * @return bool         Returns `true` if the user has the required user type, otherwise `false`.
     */
    public function create(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    /**
     * Determine whether the user can update existing disclaimers.
     *
     * This method governs the permission to modify the content or attributes of existing disclaimer records.
     * Similar to creation, updating is restricted to users with `Administrator` or `Developer` user types to ensure that only authorized personnel can alter critical disclaimer texts.
     *
     * @param  User $user  The user attempting to update a disclaimer.
     * @return bool        Returns `true` if the user has the required user type, otherwise `false`.
     */
    public function update(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }

    /**
     * Determine whether the user can delete disclaimers.
     *
     * This method controls the ability to remove disclaimer entries from the database.
     * Deletion is considered a highly sensitive action and is therefore limited exclusively to users with `Administrator` or `Developer` user types, preventing unauthorized removal of important legal or informational content.
     *
     * @param  User  $user  The user attempting to delete a disclaimer.
     * @return bool         Returns `true` if the user has the required user type, otherwise `false`.
     */
    public function delete(User $user): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer]);
    }
}
