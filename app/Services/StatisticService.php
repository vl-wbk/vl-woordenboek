<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Article;
use App\Models\User;
use App\UserTypes;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use OwenIt\Auditing\Models\Audit;

/**
 * Class StatisticService
 *
 * This class provides methods to retrieve various statistics related to the application, such as article views, user counts, and trend data for charts.
 * It leverages the 'flowframe/trend' package for generating trend data and Eloquent models for retrieving counts and sums.
 *
 * @package App\Services
 */
final readonly class StatisticService
{
    /**
     * Constant representing the string 'perWeek'. Used as a parameter for the `flowframe/trend` package to specify weekly trend intervals.
     *
     * @var string
     */
    private const WEEKLY = 'perWeek';

    /**
     * Retrieves the total number of article views.
     * This method calculates the sum of the 'views' column across all articles in the database.
     *
     * @return string The total number of article views.
     */
    public function getArticleViews(): string
    {
        return toHumanReadableNumber(number: (float) Article::sum('views'));
    }

    /**
     * Retrieves the total count of articles.
     * This method queries the database to count the total number of articles.
     *
     * @return string The total count of articles.
     */
    public function getArticleCount(): string
    {
        return toHumanReadableNumber(number: Article::count());
    }

    /**
     * Retrieves the total count of edits made to articles.
     * This method queries the audit table to count the total number of edits (audit records) made to articles.
     *
     * @return string The total count of edits made to articles.
     */
    public function getEditCount(): string
    {
        return toHumanReadableNumber(number: Audit::count());
    }

    /**
     * Retrieves the total count of registered users.
     * This method queries the database to count the total number of registered users.
     *
     * @return string The total count of registered users.
     */
    public function getUserCount(): string
    {
        return toHumanReadableNumber(number: User::count());
    }

    /**
     * Retrieves the count of non-'Normal' users (e.g., volunteers, administrators).
     *
     * This method queries the database to count the number of users whose 'user_type' is not 'Normal'.
     * This is used to determine the number of volunteers and administrators in the system.
     *
     * @return string The count of non-'Normal' users.
     */
    public function getVolunteerCount(): string
    {
        return toHumanReadableNumber(number: User::whereNot('user_type', UserTypes::Normal)->count());
    }

    /**
     * Retrieves the count of users who registered on the current date.
     * This method queries the database to count the number of users whose 'created_at' date matches the current date.
     *
     * @return int The count of users who registered today.
     */
    public function registeredToday(): int
    {
        return User::whereDate('created_at', now()->today())->count();
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
     * Formats the trend data into a structure suitable for charts.
     * This method takes a collection of `TrendValue` objects and extracts the aggregate values and date labels into separate collections.
     *
     * @param  Collection<int, TrendValue> $trendData A collection of `TrendValue` objects.
     * @return array{data: Collection<int, string>, labels: Collection<int, string>}
     */
    private function formatChartData(Collection $trendData): array
    {
        return [
            'data' => $this->extractTrendValues($trendData),
            'labels' => $this->extractTrendLabels($trendData),
        ];
    }

    /**
     * Extracts the aggregate values from a TrendValue collection.
     * This method takes a collection of `TrendValue` objects and extracts the 'aggregate' property from each object into a new collection.
     *
     * @param  Collection<int, TrendValue> $trendData  A collection of `TrendValue` objects.
     * @return Collection<int, string>                 A collection of aggregate values.
     */
    private function extractTrendValues(Collection $trendData): Collection
    {
        return $trendData->map(fn (TrendValue $value): mixed => $value->aggregate);
    }

    /**
     * Extracts the date labels from a TrendValue collection.
     * This method takes a collection of `TrendValue` objects and extracts the 'date' property from each object into a new collection.
     *
     * @param  Collection<int, TrendValue> $trendData A collection of `TrendValue` objects.
     * @return Collection<int, string>                A collection of date labels.
     */
    private function extractTrendLabels(Collection $trendData): Collection
    {
        return $trendData->map(fn (TrendValue $value): string => $value->date);
    }
}
