<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

final class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        /** @phpstan-ignore-next-line */
        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $this->updateVerifiedUser($user, $input);
        } else {
            $user->forceFill([
                'firstname' => $input['firstname'],
                'lastname' => $input['lastname'],
                'email' => $input['email'],
            ])->save();
        }
    }

    /**
     * Update the given verified user's profile information.
     *
     * @param  array<string, string>  $input
     */
    private function updateVerifiedUser(User $user, array $input): void
    {
        $user->forceFill([
            'firstname' => $input['firstname'],
            'lastname' => $input['lastname'],
            'email' => $input['email'],
            'email_verified_at' => null,
        ])->save();

        $user->sendEmailVerificationNotification();
    }
}
