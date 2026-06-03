<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Models\Audit;

/**
 * Class StatisticService
 *
 * This class provides methods to retrieve various statistics related to the application, such as article views, user counts, and trend data for charts.
 * It leverages the 'flowframe/trend' package for generating trend data and Eloquent models for retrieving counts and sums.
 */
final class StatisticService
{
    /**
     * Constant representing the string 'perWeek'. Used as a parameter for the `flowframe/trend` package to specify weekly trend intervals.
     */
    private const string WEEKLY = 'perWeek';

    /**
     * Defines the cache time-to-live (TTL) settings for this class.
     *
     * This property holds an array of TTL values, representing the duration (in seconds) for which cached data remains valid.
     * The first value (0) typically indicates no caching, while the second value (900) sets a 15-minute cache duration.
     * These values can be used to control how long certain data should be stored in cache, allowing for flexible cache strategies depending on the context or environment.
     *
     * @var array{0: int, 1: int}
     */
    private array $cacheTTL = [0, 900];

    /**
     * Retrieves the total number of article views.
     * This method calculates the sum of the 'views' column across all articles in the database.
     *
     * @return string The total number of article views.
     */
    public function getArticleViews(): string
    {
        return $this->cached('article_views', fn () => toHumanReadableNumber((float) Article::sum('views')));
    }

    /**
     * Retrieves the total count of articles.
     * This method queries the database to count the total number of articles.
     *
     * @return string The total count of articles.
     */
    public function getArticleCount(): string
    {
        return $this->cached('article_count', fn () => toHumanReadableNumber(Article::count()));
    }

    /**
     * Retrieves the count of users who registered on the current date.
     * This method queries the database to count the number of users whose 'created_at' date matches the current date.
     *
     * @return int The count of users who registered today.
     */
    public function registeredToday(): int
    {
        return Cache::flexible(
            key: 'registered_today_count',
            ttl: $this->cacheTTL,
            callback: fn (): int => User::whereDate('created_at', now()->today())->count(),
        );
    }

    /**
     * Generates data for a weekly user registration trend chart over the past year.
     *
     * This method uses the `flowframe/trend` package to generate a weekly trend of user registrations over the past year.
     * It then formats the data into a structure suitable for charts.
     *
     * @return array{data: Collection<int, string>, labels: Collection<int, string>}
     */
    public function userRegistrationChartData(): array
    {
        $registrationTrend = Trend::model(User::class)
            ->between(start: now()->subYear(), end: now())
            ->{self::WEEKLY}()
            ->count();

        return $this->formatChartData($registrationTrend);
    }

    /**
     * Generates data for a weekly trend chart of created, published, and archived articles over the past year.
     *
     * This method uses the `flowframe/trend` package to generate weekly trends for created, published, and archived articles over the past year.
     * It then extracts the data and labels into separate collections for use in charts.
     *
     * @return array<mixed>
     */
    public function articleChartData(): array
    {
        $oneYearAgo = now()->subYear();
        $today = now();
        $weekly = self::WEEKLY;

        $archivedTrend = Trend::model(Article::class)
            ->between(start: $oneYearAgo, end: $today)
            ->dateColumn('archived_at')
            ->$weekly()
            ->count();

        $createdTrend = Trend::model(Article::class)
            ->between(start: $oneYearAgo, end: $today)
            ->$weekly()
            ->count();

        $publishedTrend = Trend::model(Article::class)
            ->between(start: $oneYearAgo, end: $today)
            ->dateColumn('published_at')
            ->$weekly()
            ->count();

        return [
            'archived' => $this->extractTrendValues($archivedTrend),
            'created' => $this->extractTrendValues($createdTrend),
            'published' => $this->extractTrendValues($publishedTrend),
            'labels' => $this->extractTrendLabels($createdTrend),
        ];
    }

    /**
     * Retrieves application metrics formatted for display.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getMetrics(): array
    {
        $thisWeek = [now()->startOfWeek(), now()->endOfDay()];
        $lastWeek = [now()->subWeek()->startOfWeek(), now()->subWeek()->endOfWeek()];

        return [
            $this->formatMetric('Artikelweergaves', 'eye', 'views',
                (int) Article::whereBetween('updated_at', $thisWeek)->sum('views'),
                (int) Article::whereBetween('updated_at', $lastWeek)->sum('views')
            ),
            $this->formatMetric('Aantal artikelen', 'document-text', 'articles',
                Article::whereBetween('created_at', $thisWeek)->count(),
                Article::whereBetween('created_at', $lastWeek)->count()
            ),
            $this->formatMetric('Bewerkingen', 'pencil', 'edits',
                Audit::whereBetween('created_at', $thisWeek)->count(),
                Audit::whereBetween('created_at', $lastWeek)->count()
            ),
            $this->formatMetric('Nieuwe gebruikers', 'users', 'volunteers',
                User::whereBetween('created_at', $thisWeek)->count(),
                User::whereBetween('created_at', $lastWeek)->count(),
            ),
        ];
    }

    /**
     * Internal helper to handle cache logic.
     */
    private function cached(string $key, callable $callback): string
    {
        return Cache::flexible($key, $this->cacheTTL, $callback);
    }

    /**
     * Internal helper to build metric arrays.
     */
    private function formatMetric(string $title, string $icon, string $color, int|float $current, int|float $prev): array
    {
        return array_merge(
            ['title' => $title, 'value' => toHumanReadableNumber($current), 'icon' => $icon, 'color' => $color],
            $this->weekTrend((int) $current, (int) $prev)
        );
    }

    /**
     * Calculates the weekly trend percentage.
     */
    private function weekTrend(int $current, int $previous): array
    {
        if ($previous === 0) {
            return [
                'trend' => $current > 0 ? 'nieuw' : '—',
                'trend_label' => '',
                'up' => $current > 0 ? true : null,
            ];
        }

        $pct = (int) round((($current - $previous) / $previous) * 100);

        return [
            'trend' => ($pct > 0 ? '+' : '').$pct.'%',
            'trend_label' => 'vs vorige week',
            'up' => match (true) {
                $pct > 0 => true,
                $pct < 0 => false,
                default => null,
            },
        ];
    }

    /**
     * Formats the trend data into a structure suitable for charts.
     *
     * @param  Collection<int, TrendValue> $trendData
     * @return array{data: Collection<int, string>, labels: Collection<int, string>}
     */
    private function formatChartData(Collection $trendData): array
    {
        return [
            'data' => $this->extractTrendValues($trendData),
            'labels' => $this->extractTrendLabels($trendData),
        ];
    }

    private function extractTrendValues(Collection $trendData): Collection
    {
        return $trendData->map(fn (TrendValue $value): mixed => $value->aggregate);
    }

    private function extractTrendLabels(Collection $trendData): Collection
    {
        return $trendData->map(fn (TrendValue $value): string => $value->date);
    }
}
