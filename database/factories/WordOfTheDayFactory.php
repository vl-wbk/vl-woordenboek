<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WordOfTheDay>
 */
final class WordOfTheDayFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scheduled_by' => User::factory()->create()->id,
            'article_id' => Article::factory()->create()->id, 
            'scheduled_for' => now()->subDays(rand(0, 150)),
            'scheduling_reason' => $this->faker->paragraph()
        ];
    }
}
