<?php

namespace Database\Factories;

use App\Models\{User, Article};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Note>
 */
class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => $this->withAuthor(),
            'article_id' => $this->withArticle(),
            'title' => $this->faker->sentence(),
            'body' => $this->faker->paragraph(),
        ];
    }

    private function withAuthor(): int|UserFactory
    {
        $randomUser = User::inRandomOrder()->first();

        if ($randomUser) {
            /** @var User $randomUser */
            return $randomUser->id;
        }

        return User::factory();
    }

    private function withArticle(): int|UserFactory
    {
        $randomArticle = Article::inRandomOrder()->first();

        if ($randomArticle) {
            /** @var Article $randomArticle */
            return $randomArticle->id;
        }

        return Article::factory();
    }
}
