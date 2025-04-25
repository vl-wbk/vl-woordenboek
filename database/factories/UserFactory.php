<?php

namespace Database\Factories;

use App\Models\User;
use App\UserTypes;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * @return Factory<User>
     */
    public function developer(): Factory
    {
        return $this->state(fn (): array => ['user_type' => UserTypes::Developer]);
    }

    /**
     * @return Factory<User>
     */
    public function administor(): Factory
    {
        return $this->state(fn (): array => ['user_type' => UserTypes::Administrators]);
    }

    /**
     * @return Factory<User>
     */
    public function editor(): Factory
    {
        return $this->state(fn (): array => ['user_type' => UserTypes::Editor]);
    }

    /**
     * @return Factory<User>
     */
    public function editorInChief(): Factory
    {
        return $this->state(fn (): array => ['user_type' => UserTypes::EditorInChief]);
    }
}
