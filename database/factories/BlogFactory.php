<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
final class BlogFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(Status::cases())->value,
            "author_id" => $this->withAuthor(),
            'views' => $this->faker->numberBetween(0, 1000),
            'title' => fake('nl_BE')->sentence(),
            'content' => fake('nl_BE')->sentences(100, asText: true)
        ];
    }

    private function withAuthor()
    {
        if ($randomUser = User::inRandomOrder()->first()) {
            return $randomUser->id;
        }

        return User::factory();
    }
}
