<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Concept;
use App\Models\PartOfSpeech;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Concept>
 */
final class ConceptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(), 
            'part_of_speech_id' => null,
            'word' => $this->faker->word, 
            'characteristics' => $this->faker->word,
            'description' => $this->faker->sentence,
            'notify_author' => $this->faker->boolean,
        ];
    }
}
