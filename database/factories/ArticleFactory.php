<?php

namespace Database\Factories;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Models\User;
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
            'word' => fake()->word(),
            'views' => fake()->numberBetween(0, 1000),
            'status' => LanguageStatus::Onbekend,
            'image_url' => fake()->url(),
            'description' => fake()->paragraph(),
            /** @phpstan-ignore-next-line */
            'keywords' => implode(',', fake()->words(3)),
            'example' => fake()->sentence,
            'characteristics' => fake()->paragraph,
            'sources' => json_encode([fake()->url, fake()->url]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function archived(): Factory
    {
        return $this->state(function (array $attributes): array {
            return ['state' => ArticleStates::Archived, 'archived_at' => now(), 'archiving_reason' => fake()->sentence, 'archiever_id' => User::factory()->create()->id];
        });
    }

    public function published(): Factory
    {
        return $this->state(function (array $attributes): array {
            return ['state' => ArticleStates::Published, 'published_at' => now(), 'publisher_id' => User::factory()->create()->id];
        });
    }
}
