<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use App\States\Reporting\Status;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Lottery;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleReport>
 */
final class ArticleReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $state = $this->faker->randomElement(Status::cases());

        return [
            'state' => $state->value,
            'author_id' => $this->getAssignee(),
            'article_id' => Article::factory(),
            'assignee_id' => $this->getAssignee(),
            'description' => $this->faker->sentence,
            'assigned_at' => function (array $attributes) use ($state) {
                return $state->in([Status::Closed, Status::InProgress])
                    ? now()
                    : null;
            },
            'closed_at' => function (array $attributes) use ($state) {
                return $state->in([Status::Closed, Status::InProgress])
                    ? now()
                    : null;
            },
        ];
    }

    private function getAssignee(): Lottery
    {
         return Lottery::odds(1, 2)->winner(function () {
            return (User::query()->count())
                ? User::query()->inRandomOrder()->first()
                : User::factory();
         })->loser(function () {
             return null;
         });
    }
}
