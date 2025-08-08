<?php

namespace Database\Factories;

use App\Enums\FeedbackStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
final class FeedbackFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'status' => FeedbackStatus::Unprocessed,
            'first_time_visit' => 'Ja',
            'results_found_easily' => 'Nee',
            'visit_reason' => $this->faker->text(),
            'search_additional_info' => $this->faker->text(),
            'additional_info' => $this->faker->text(),
            'contact_allowed' => $this->faker->boolean(),
        ];
    }
}
