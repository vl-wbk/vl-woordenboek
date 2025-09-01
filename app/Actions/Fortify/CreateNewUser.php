<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use App\Data\Account\UserRegistrationData;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

/**
 * Handles the creation of new user accounts within the application, typically in response to a user registration request.
 * This action is integrated with Laravel Fortify, serving as the concrete implementation of the CreatesNewUsers contract.
 * It is responsible for validating the provided registration input and persisting a new `User` record in the database.
 *
 * This class ensures that all necessary data for a new user, such as name, email, and password, meets predefined validation rules before an account is successfully created.
 * It leverages a Data Transfer Object (DTO) for structured data handling.
 *
 * @see CreatesNewUsers         - The Fortify contract this class implements.
 * @see User                    - The Eloquent model representing application users.
 * @see UserRegistrationData    - The DTO used for user registration data.
 *
 * @package App\Actions\Fortify
 */
final class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user account.
     *
     * This method is the core logic for user registration.
     * It receives an array of input data from the registration form and performs a comprehensive validation check.
     *
     * The validation rules ensure:
     *
     * - `voornaam` (first name) and `achternaam` (last name) are required strings and do not exceed 255 characters.
     * - `email` is required, a valid email format, and unique in the `users` table, preventing duplicate accounts.
     * - `password` adheres to a set of predefined rules imported via the `PasswordValidationRules` trait, enforcing strong password requirements (e.g., minimum length, complexity).
     * - `agreement` (terms and conditions checkbox) must be accepted. A custom error message is provided if this condition is not met.
     *
     * If validation fails, an `Illuminate\Validation\ValidationException` is thrown automatically.
     * If validation passes, the input data is mapped to a UserRegistrationData DTO, which then converts it to an array suitable for creating a new User model instance in the database.
     *
     * Developers maintaining this code should note that any changes to validation rules or the user creation process should be made here.
     *
     * @param  array<string, string> $input  An associative array containing the user's registration data, typically from a form submission. Expected keys include 'voornaam', 'achternaam', 'email', 'password', and 'agreement'.
     * @return User|Model                    The newly created `User` model instance.
     *
     * @throws \Illuminate\Validation\ValidationException If any validation rule fails.
     */
    public function create(array $input): User|Model
    {
        Validator::make($input, [
            'gebruikersnaam' => ['required', 'string', 'max:255', Rule::unique('users', 'name')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)],
            'password' => $this->passwordRules(),
            'agreement' => ['accepted'],
        ], [
            'agreement.accepted' => 'De algemene voorwaarden moeten geaccepteerd worden alvorens een account aan te maken',
        ])->validate();

        return User::create($this->userRegistrationData($input)->toArray());
    }

    /**
     * Maps the raw input array from the registration form to a `UserRegistrationData` DTO.
     *
     * This private helper method is responsible for taking the validated input data and transforming it into a structured UserRegistrationData object.
     * Using a DTO enhances type safety and readability by explicitly defining the expected data structure for user registration, making the data flow clearer throughout the application.
     * It also decouples the internal data representation from the raw form input.
     *
     * Maintainers can easily see what data is expected for user registration and how it's mapped from the input array.
     *
     * @param array<string, string> $input  An associative array containing the raw registration input.
     * @return UserRegistrationData         A structured DTO containing the user's registration details.
     */
    private function userRegistrationData(array $input): UserRegistrationData
    {
        return new UserRegistrationData(
            name: $input['gebruikersnaam'],
            email: $input['email'],
            password: $input['password'],
        );
    }
}
