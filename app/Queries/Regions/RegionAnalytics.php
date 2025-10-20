<?php

declare(strict_types=1);

namespace App\Queries\Regions;

use App\Builders\ArticleBuilder;
use App\Models\Article;
use App\Models\ArticleReport;
use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Provides analytics and statistical insights for a specific region.
 *
 * The RegionAnalytics class aggregates various statistics related to a given region, such as the number of published articles, total views, unique contributors, and the number of reports associated with articles from that region.
 * These analytics are useful for dashboards, reports, or administrative panels where understanding regional activity and engagement is important.
 *
 * Each metric is calculated in a dedicated private method, ensuring separation of concerns and making the codebase easier to maintain and extend.
 * The results are returned in a human-readable format, suitable for direct display in the UI.
 *
 * @package App\Queries\Regions
 */
final readonly class RegionAnalytics
{
    /**
     * Fetches all analytics for the given region.
     *
     * This method collects statistics for views, articles, contributors, and reports,
     * and returns them as a collection. Each metric is calculated using a dedicated
     * helper method, and the results are formatted for easy consumption by the UI.
     *
     * @param  Region $region                             The region for which analytics are being fetched.
     * @return Collection<string, array<string, string>>  A collection of analytics data, keyed by metric type.
     */
    public function fetch(Region $region): Collection
    {
        return collect([
            'views' => $this->getViewAnalytics($region),
            'word' => $this->getArticleAnalytics($region),
            'contributor' => $this->getContributorAnalytics($region),
            'report' => $this->getReportAnalytics($region),
        ]);
    }

    /**
     * Calculates the number of published articles for the region and its percentage of the total.
     *
     * Returns both a human-readable count and a percentage string describing the region's
     * share of all published articles.
     *
     * @param Region $region          The region to analyze.
     * @return array<string, string>  Contains 'statistic' (article count) and 'altText' (percentage description).
     */
    private function getArticleAnalytics(Region $region): array
    {
        return ['statistic' => toHumanReadableNumber(
            $region->articles()->published()->count()
        )];
    }

    /**
     * Calculates the total number of views for published articles in the region and its percentage of all views.
     * Returns both a human-readable view count and a percentage string describing the region's share of all article views.
     *
     * @param  Region $region         The region to analyze.
     * @return array<string, string>  Contains 'statistic' (view count) and 'altText' (percentage description).
     */
    private function getViewAnalytics(Region $region): array
    {
        return ['statistic' => toHumanReadableNumber(
            (int) $region->articles()->whereNotNull('published_at')->sum('views')
        )];
    }

    /**
     * Calculates the number of unique contributors and their total contributions for the region.
     * This method determines how many unique users have contributed published articles to the region, as well as the total number of such articles. The results are formatted for display.
     *
     * @param  Region $region         The region to analyze.
     * @return array<string, string>  Contains 'statistic' (unique contributor count) and 'altText' (total contributions).
     */
    private function getContributorAnalytics(Region $region): array
    {
        $totalUniqueAuthors = User::whereHas('suggestions', function ($suggestionQuery) use ($region): void {
            $suggestionQuery->whereNotNull('published_at');

            /** @phpstan-ignore-next-line */
            $suggestionQuery->whereHas('regions', function ($regionQuery) use ($region): void {
                $regionQuery->where('regions.id', $region->id);
            });
        })->count();

        return ['statistic' => toHumanReadableNumber($totalUniqueAuthors)];
    }

    /**
     * Calculates the number of reports associated with articles in the region and its percentage of all reports.
     * This method finds all reports linked to articles from the region and compares it to the total number of reports in the system, returning both a count and a percentage description.
     *
     * @param  Region $region          The region to analyze.
     * @return array<string, string>  Contains 'statistic' (report count) and 'altText' (percentage description).
     */
    private function getReportAnalytics(Region $region): array
    {
        /** @phpstan-ignore-next-line */
        $totalAttachedReports = ArticleReport::whereHas('article', function (ArticleBuilder $articleQuery) use ($region): void {
            $articleQuery->whereNotNull('published_at');

            $articleQuery->whereHas('regions', function (Builder $regionQuery) use ($region): void {
                $regionQuery->where('regions.id', $region->id);
            });
        })->count();

        return ['statistic' => toHumanReadableNumber($totalAttachedReports)];
    }
}
