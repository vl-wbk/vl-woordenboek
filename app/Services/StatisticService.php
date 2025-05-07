<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ArticleStates;
use App\Models\Article;
use App\Models\User;
use App\States\Articles\ArticleState;
use App\UserTypes;
use Cog\Laravel\Ban\Models\Ban;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use OwenIt\Auditing\Models\Audit;

final readonly class StatisticService
{
    public function getArticleViews(): int
    {
        return (int) Article::sum('views');
    }

    public function getArticleCount(): int
    {
        return Article::count();
    }

    public function getEditCount(): int
    {
        return Audit::count();
    }

    public function getUserCount(): int
    {
        return User::count();
    }

    public function getVolunteerCount(): int
    {
        return User::whereNot('user_type', UserTypes::Normal)->count();
    }

    public function registeredToday(): int
    {
        return User::where('created_at', now())->count();
    }

    public function userRegistrationChartData(): array
    {
        $data = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->perWeek()
            ->count();

        return [
            'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    public function articleChartData(): array
    {
        $archieved =  Trend::model(Article::class)
            ->between(start: now()->subYear(), end: now())
            ->dateColumn('archived_at')
            ->perWeek()
            ->count();

        $published = Trend::model(Article::class)
            ->between(start: now()->subYear(), end: now())
            ->dateColumn('published_at')
            ->perWeek()
            ->count();

        $created = Trend::model(Article::class)
            ->between(start: now()->subYear(), end: now())
            ->perWeek()
            ->count();

            return [
                'archived' => $archieved->map(fn (TrendValue $value) => $value->aggregate),
                'created' => $created->map(fn (TrendValue $value) => $value->aggregate),
                'published' => $published->map(fn (TrendValue $value) => $value->aggregate),
                'labels' => $created->map(fn (TrendValue $value) => $value->date),
            ];
    }
}
