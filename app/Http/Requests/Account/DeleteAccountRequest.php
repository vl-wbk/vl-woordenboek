<?php

declare(strict_types=1);

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

/**
 * DeleteAccountRequest validates user input for account deletion requests.
 *
 * This form request ensures that users provide their current password before their account can be deleted.
 * This security measure prevents unauthorized account deletions and requires users to consciously confirm their action by re-entering their credentials.
 *
 * @package App\Http\Requests\Account
 */
final class DeleteAccountRequest extends FormRequest
{
    /**
     * Defines validation rules for the account deletion request.
     *
     * Requires the user's current password to be provided and validated against their stored credentials.
     * The 'current_password' rule automatically handles the password verification against the authenticated user's account.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['password' => ['required', 'current_password']];
    }
}
