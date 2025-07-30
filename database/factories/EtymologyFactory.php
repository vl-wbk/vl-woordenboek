<?php

namespace Database\Factories;

use App\Models\{Article, User};
use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etymology>
 */
class EtymologyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'period_start' => $this->faker->dateTime(),
            'period_end' => $this->faker->dateTime(),
            'status' => $this->faker->randomElement(EtymologyStatus::cases())->value,
            'type' => $this->faker->randomElement(EtymologyTypes::cases())->value,
            'article_id' => $this->withArticle(),
            'author_id' => $this->withAuthor(),
            'rejected_by' => $this->withAuthor(),
            'archived_by' => $this->withAuthor(),
            'published_by' => $this->withAuthor(),
            'origin_form' => $this->faker->word(),
            'origin_language' => $this->faker->languageCode(),
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

    private function withArticle(): int|ArticleFactory
    {
        $randomArticle = Article::inRandomOrder()->first();

        if ($randomArticle) {
            /** @var Article $randomArticle */
            return $randomArticle->id;
        }

        return Article::factory();
    }
}
