<?php

namespace Database\Factories;

use App\Enums\Articles\EtymologySources;
use App\Models\{Article, User};
use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @todo GH #294 apply the new data structure to the seeder
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Etymology>
 */
final class EtymologyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'etymology' => $this->faker->word,
			'origin' => $this->faker->word,
			'origin_period' => $this->faker->numberBetween(500, date('Y')),
			'further_development' => $this->faker->paragraph,
			'oldest_find_spot' => $this->faker->city,
			'oldest_find_period' => $this->faker->numberBetween(500, date('Y')),
			'additional_info' => $this->faker->sentence,
			'source_name' => EtymologySources::EtymologieBank->value,
			'source_hyperlink' => $this->faker->url,
			'status' => EtymologyStatus::Draft->value,
			'article_id' => $this->withArticle(),
			'author_id' => $this->withAuthor(),
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
