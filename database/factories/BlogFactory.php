<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Filament\Clusters\Blog\Resources\Blogs\Enums\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * The BlogFactory class is an Eloquent model factory responsible for generating fake or test data for the `Blog` model.
 * It provides a convenient and flexible way to create multiple blog post instances with various attributes, including their status, author, view count, title, and content.
 *
 * This factory extends Laravel's base `Factory` class, offering a `definition` method to specify the default structure of a blog post.
 * It also includes a private helper method (`withAuthor`) to intelligently associate existing or newly created authors with the generated blog posts.
 * The factory leverages the `Faker` library to produce realistic-looking data for textual fields and numerical values.
 *
 * @see Blog    - The Eloquent model this factory generates.
 * @see User    - The Eloquent model for the blog post's author.
 * @see Status  - The enum defining possible statuses for a blog post.
 *
 * @extends Factory<\App\Models\Blog>
 *
 * @package Database\Factories
 */
final class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * This method specifies the default attributes that a `Blog` model will have when created using this factory without any specific state applied.
     * It populates several key fields:
     *
     * - `status`:      A random status is selected from the `Status` enum cases, ensuring variety in the generated blog post states (e.g., draft, published).
     * - `author_id`:   This is dynamically assigned by calling the `withAuthor()` helper method, ensuring that each generated blog post is linked to a valid author, either an existing one or a newly created one.
     * - `views`:       A random integer between 0 and 1000 is generated to simulate the number of views a blog post might have received.
     * - `title`:       A random sentence is generated using the Faker instance, specifically configured for Belgian Dutch (`nl_BE`) locale for more realistic titles in that context.
     * - `content`:     A longer body of text consisting of 100 sentences is generated as a single string, also using the `nl_BE` locale, to provide substantial content for the blog post.
     *
     * @return array<string, mixed>     An associative array representing the default attributes for a `Blog` model.
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(Status::cases())->value,
            "author_id" => $this->withAuthor(),
            'views' => $this->faker->numberBetween(0, 1000),
            'title' => fake('nl_BE')->sentence(),
            'content' => fake('nl_BE')->sentences(100, asText: true),
        ];
    }

    /**
     * Determines and returns the ID of an author for the blog post.
     *
     * This private helper method intelligently assigns an author to the blog post.
     * It first attempts to locate a random existing `User` record in the database.
     * If a user is successfully found, their `id` is returned, thereby associating the blog post with an already existing author.
     * In scenarios where no users are present in the database (e.g., during the very first seeding operations before user accounts are created), the method gracefully falls back to returning a new `UserFactory` instance.
     * This factory instance will then be responsible for creating a new user record when the blog post is persisted, guaranteeing that every generated blog post always has a valid and associated author.
     *
     * @return int|UserFactory The ID of an existing user, or a `UserFactory` instance if no users are found in the database.
     */
    private function withAuthor(): int|UserFactory
    {
        $randomUser = User::inRandomOrder()->first();

        if ($randomUser) {
            /** @var User $randomUser */
            return $randomUser->id;
        }

        return User::factory();
    }
}
