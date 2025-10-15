<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Article;
use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * The ArticleFactory class is an Eloquent model factory dedicated to generating
 * fake or test data for the `Article` model. It provides a robust mechanism to
 * create various article instances with predefined attributes and allows for
 * the definition of specific states, such as archived or published, for more
 * complex testing scenarios.
 *
 * This factory extends Laravel's base `Factory` class, offering a `definition`
 * method to establish the default properties of an article. It also includes
 * specialized state methods (`archived`, `published`) to easily generate
 * articles reflecting specific lifecycle stages. The factory leverages the `Faker`
 * library to populate fields with realistic dummy data.
 *
 * @see Article         - The Eloquent model this factory generates.
 * @see ArticleStates   - The enum defining the possible states of an article.
 * @see LanguageStatus  - The enum defining the language status of an article.
 * @see User            - The Eloquent model for users, potentially acting as authors, editors, or publishers.
 *
 * @extends Factory<Article>
 * @package Database\Factories
 */
final class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This method specifies the default attributes that an `Article` model will have when created using this factory without any specific state applied.
     * It populates various fields with generated or default values. The `state` attribute is assigned a random value chosen from the `ArticleStates` enum, providing initial variability.
     * Both `part_of_speech_id`, `author_id`, and `editor_id` are set to `null` by default, indicating these relationships are typically established explicitly or are optional.
     *
     * The `word` attribute receives a single random word from Faker.
     * The `views` attribute is populated with a random integer between 0 and 1000.
     * The `status` is defaulted to `LanguageStatus::Onbekend`, implying an initially undefined language status. For visual content, `image_url` is a random URL, and a brief `description` is a random paragraph.
     * The `keywords` attribute is a comma-separated string of three random words.
     * An `example` of usage is a random sentence, and `characteristics` are described by a random paragraph.
     * The `sources` attribute is initialized as an empty array. Finally, `created_at`, `published_at`, and `updated_at` timestamps are all set to the current time.
     *
     * @return array<string, mixed> An associative array representing the default attributes for an `Article` model.
     */
    public function definition(): array
    {
        return [
            'state' => $this->faker->randomElement(ArticleStates::cases())->value,
            'part_of_speech_id' => null,
            'author_id' => User::factory()->create()->id,
            'editor_id' => User::factory()->create()->id,
            'word' => fake()->word(),
            'views' => fake()->numberBetween(0, 1000),
            'status' => LanguageStatus::Onbekend,
            'image_url' => fake()->url(),
            'description' => fake()->paragraph(),
            /** @phpstan-ignore-next-line */
            'keywords' => implode(',', fake()->words(3)),
            'example' => fake()->sentence,
            'characteristics' => fake()->paragraph,
            'created_at' => now(),
            'published_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Indicate that the article's state should be set to 'Archived'.
     *
     * This state method modifies the default attributes of an article to represent an archived status.
     * It specifically sets the `state` to `ArticleStates::Archived`.
     * It also records the precise moment of archival by setting the `archived_at` timestamp to the current time.
     * A `random archiving_reason` is generated using Faker, and an `archiever_id` is assigned by creating a new user via the `User::factory()` and immediately retrieving its ID.
     * This is useful for testing archival workflows and ensuring associated data like the archiver and reason are present.
     *
     * @return Factory<Article>     Returns the factory instance, typed to the `Article` model, allowing for method chaining.
     */
    public function archived(): Factory
    {
        return $this->state(fn(): array => ['state' => ArticleStates::Archived, 'archived_at' => now(), 'archiving_reason' => fake()->sentence, 'archiever_id' => User::factory()->create()->id]);
    }

    /**
     * Indicate that the article's state should be set to 'Published'.
     *
     * This state method modifies the default attributes of an article to represent a published status.
     * It sets the `state` to `ArticleStates::Published`, records the `published_at` timestamp as the current time, and assigns a publisher_id` by creating a new user via the `User::factory()`
     * and immediately retrieving its ID.
     *
     * This is beneficial for testing public-facing functionalities that require published content and an associated publisher.
     *
     * @return Factory<Article>     Returns the factory instance, typed to the `Article` model, allowing for method chaining.
     */
    public function published(): Factory
    {
        return $this->state(fn(): array => ['state' => ArticleStates::Published, 'published_at' => now(), 'publisher_id' => User::factory()->create()->id]);
    }
}
