<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\VolunteerApplicationState;
use App\Enums\VolunteerPositions;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VolunteerApplication>
 */
final class VolunteerApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state' => VolunteerApplicationState::Open->value,
            'volunteer_id' => User::factory()->create()->id, 
            'role' => VolunteerPositions::Editor->value,
            'motivation' => $this->faker->paragraph,
            'background' => $this->faker->paragraph,
        ];
    }
}
