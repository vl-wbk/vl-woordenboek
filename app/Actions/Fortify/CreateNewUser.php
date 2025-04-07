<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Data\Account\UserRegistrationData;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'voornaam' => ['required', 'string', 'max:255'],
            'achternaam' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create($this->userRegistrationData($input)->toArray());
    }

    /**
     * @param array<string, string> $input
     */
    private function userRegistrationData(array $input): UserRegistrationData
    {
        return new UserRegistrationData(
            firstname: $input['voornaam'],
            lastname: $input['achternaam'],
            email: $input['email'],
            password: $input['password']
        );
    }
}
