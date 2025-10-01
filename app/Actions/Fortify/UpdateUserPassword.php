<?php

namespace App\Actions\Fortify;

use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;

/**
 * The UpdateUserPassword class is responsible for handling the logic for updating a user's password within the application, typically integrated with Laravel Fortify's user profile management features.
 * This action ensures that password updates are performed securely by validating the current password and applying strong password rules to the new password before hashing and storing it.
 *
 * @see UpdatesUserPasswords    - The Fortify contract this class implements.
 * @see User                    - The Eloquent model representing application users.
 *
 * @package App\Actions\Fortify
 */
final class UpdateUserPassword implements UpdatesUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * This method handles the process of changing an authenticated user's password.
     * It performs crucial validation steps to ensure security and data integrity.
     *
     * The validation rules applied are:
     *
     * - `current_password`: This field is required, must be a string, and importantly, it must match the user's currently authenticated password. A custom validation message is provided for clarity if this check fails.
     *
     * - `password`:         This field is subject to the application's defined password complexity rules, which are imported via the `PasswordValidationRules` trait.
     *                       These rules enforce requirements like minimum length, presence of uppercase, lowercase, numbers, and symbols.
     *
     * The validation uses `validateWithBag('updatePassword')` which means any validation errors will be placed into an error bag named 'updatePassword'.
     * This is useful for displaying errors in specific sections of a form without affecting other validation messages on the same page.
     *
     * If validation passes, the user's `password` attribute is updated directly using `forceFill` (to bypass mass assignment protection if necessary), and the new password is securely hashed using `Hash::make()`.
     * Finally, the updated user model is persisted to the database via `save()`.
     *
     * Developers should ensure that the `PasswordValidationRules` trait is correctly configured with the desired password policies.
     * Any changes to password requirements or the update flow should be implemented within this method.
     *
     * @param  User $user                       The user model instance whose password is to be updated.
     * @param  array<string, string> $input     An associative array containing the `current_password` and the new `password` (and `password_confirmation`).
     * @return void                             This method does not return a value; it updates the user's password as a side effect.
     *
     * @throws ValidationException If any validation rule fails.
     */
    public function update(User $user, array $input): void
    {
        Validator::make(
            data: $input,
            rules: ['current_password' => ['required', 'string', 'current_password:web'], 'password' => $this->passwordRules()],
            messages: ['current_password.current_password' => __('The provided password does not match your current password.')],
        )->validateWithBag('updatePassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}
