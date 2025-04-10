<?php

namespace Database\Factories;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'state' => ArticleStates::New,
            'part_of_speech_id' => null,
            'author_id' => null,
            'editor_id' => null,
            'publisher_id' => null,
            'archiever_id' => null,
            'word' => fake()->word(),
            'views' => fake()->numberBetween(0, 1000),
            'status' => LanguageStatus::Onbekend,
            'image_url' => fake()->url(),
            'description' => fake()->paragraph(),
            /** @phpstan-ignore-next-line */
            'keywords' => implode(',', fake()->words(3)),
            'example' => fake()->sentence,
            'characteristics' => fake()->paragraph,
            'archiving_reason' => fake()->sentence,
            'sources' => json_encode([fake()->url, fake()->url]),
            'archived_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'published_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
