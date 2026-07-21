<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PartOfSpeech;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory for creating PartOfSpeech môdel instances. 
 * 
 * This fctory generates dummy data for testing purposes, ensuring that our database interactions 
 * involving parts of speeh are correctly populated with randomized but valid structures.
 * 
 * @extends Factory<PartOfSpeech>
 */
final class PartOfSpeechFactory extends Factory
{
    /**
     * Define the model's default state.
     * --
     * Provides a set of randomized attributes that reflect the epected schema
     * for the PartOfSpeech model. 
     * 
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'suggestible' => $this->faker->boolean, 
            'name' =>  $this->faker->numberBetween(0, 4), 
            'value' => $this->faker->word,
        ];
    }
}
