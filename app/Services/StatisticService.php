<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use Carbon\Carbon;
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
     * The trend interval granularity identifier.
     *
     * Defines the configuration keyword passed downstream to the `flowframe/trend`
     * analytics engine to group data metrics and compile aggregates into distinct weekly intervals.
     *
     * @var string
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
     * @return int The total number of article views.
     */
    public function getArticleViews(): int
    {
        return $this->cached('article_views', fn (): int => (int) toHumanReadableNumber((float) Article::sum('views')));
    }

    /**
     * Retrieves the total count of articles.
     * This method queries the database to count the total number of articles.
     *
     * @return int The total count of articles.
     */
    public function getArticleCount(): int
    {
        return $this->cached('article_count', fn () => Article::count());
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
     * @return array{archived: Collection<int, string>, created: Collection<int, string>, labels: Collection<int, string>, published: Collection<int, string>}
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
     * Retrieve and compile gloabl application performance and usage metrics.
     *
     * This method aggregates high-level telemetry data across multiple domains-including, content interaction stats, absolute resource tallies,
     * administrative lifecycle changes and current-week acquisition velocities-formatting them into standard KPI card configurations.
     *
     * @return array<int, array{color: string, icon: string, title: string, value: string}> An array of compiled metric configurations tailored for UI result cards
     */
    public function getMetrics(): array
    {
        $thisWeek = [now()->startOfWeek(), now()->endOfDay()];

        return [
            $this->formatMetric('Artikelweergaves', 'eye', 'views',
                (int) Article::sum('views'),
            ),
            $this->formatMetric('Aantal artikelen', 'document-text', 'articles',
                Article::count(),
            ),
            $this->formatMetric('Bewerkingen', 'pencil', 'edits',
                Audit::count(),
            ),
            $this->formatMetric('Nieuwe gebruikers', 'users', 'volunteers',
                User::whereBetween('created_at', $thisWeek)->count(),
            ),
        ];
    }

    /**
     * Retrieve a value from the cache using a flexible (stale-while-revalidate) strategy, or execute the fallback.
     *
     * This internal helper wraps the framework's flexible caching mechanism to safely manage data persistence.
     * It utilizes the class-defined time-to-live configuration to serve fresg data, allow stale grace periods,
     * and cast the evaluated result into an integer.
     *
     * @param  string   $key      The unique identifier token utilized to locate or store the cached payload.
     * @param  callable $callback The fallback execution routing logic executed if the cache needs fresh synchronization.
     * @return int                The evaluation response cast cleanly as an integer representation.
     */
    private function cached(string $key, callable $callback): int
    {
        return (int) Cache::flexible($key, $this->cacheTTL, $callback);
    }

    /**
     * Format raw metric properties into a standarized structure for KPI cards.
     *
     * This method compiles metadata alongside a numeric value, converting the raw integer of float into a localized,
     * abbreviated human-readable format (e.g., 1.2k, 3.4M) for clean presentation on UI dashboards.
     *
     * @param string    $title   The display title or heading for the metric card.
     * @param string    $icon    The icon identifier of class string (e.g., Heroicon name).
     * @param string    $color   The theme color designation (e.g, Tailwind class or state key).
     * @param int|float $current The raw numerical value to be formatted and displayed.
     *
     * @return array{color: string, icon: string, title: string, value: string} The compiled associative array containing all formatted KPI card properties.
     */
    private function formatMetric(string $title, string $icon, string $color, int|float $current): array
    {
        return [
            'title' => $title,
            'value' => toHumanReadableNumber($current),
            'icon'  => $icon,
            'color' => $color,
        ];
    }

    /**
     * Format raw trend data into a structured payload optimized for chart components.
     *
     * Consolidates the dataset by extracting both the numerical trend aggregates and their corresponding
     * localized human-readable time labels into a structured associative array required by frontend graphing utilities.
     *
     * @param  Collection<int, TrendValue> $trendData A collection of raw trend data metrics.
     * @return array{data: Collection<int, string>, labels: Collection<int, string>} An associative array containing the separate data and label collections.
     */
    private function formatChartData(Collection $trendData): array
    {
        return [
            'data' => $this->extractTrendValues($trendData),
            'labels' => $this->extractTrendLabels($trendData),
        ];
    }

    /**
     * Extract the raw aggregate metrics from a collection of trend data.
     *
     * This method maps over the dataset to isolated the calculated mumeric values (such as counts, sums, or averages),
     * separating them from their associated time periods for easy consumption by charts or tables.
     *
     * @param  Collection<int, TrendValue> $trendData A collection of raw trend data entries containing aggregates.
     * @return Collection<int, string>                A collection containing only the isolated aggregate values.
     */
    private function extractTrendValues(Collection $trendData): Collection
    {
        return $trendData->map(fn (TrendValue $value): mixed => $value->aggregate);
    }

    /**
     * Extract and format display labels from a collection of trend data.
     *
     * This method iterates over raw trend metrics containing year-weaak strings,
     * parses the periods into localizec human-readable month and year representation (e.g., "January 2026")
     * based on the start of that specific ISO week.
     *
     * @param  Collection<int, TrendValue> $trendData A collection of raw trend data entries containing the date keys.
     * @return Collection<int, string>                A Collection of formatted, translated month and year strings.
     */
    private function extractTrendLabels(Collection $trendData): Collection
    {
        return $trendData->map(function (TrendValue $value): string {
            [$year, $week] = explode('-', $value->date);

            return Carbon::now()
                ->setISODate((int) $year, (int) $week)
                ->startOfWeek()
                ->translatedFormat('F Y');
        });
    }
}
