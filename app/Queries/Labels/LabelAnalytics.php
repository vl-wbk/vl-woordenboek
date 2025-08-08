<?php

declare(strict_types=1);

namespace App\Queries\Labels;

use App\Models\Article;
use App\Models\ArticleReport;
use App\Models\Label;
use App\Models\User;
use Illuminate\Support\Collection;

/**
 * Provides analytical data related to a specific `Label` model.
 * This class encapsulates the logic for fetching various statistics and metrics associated with a given label, such as article counts, views, contributor data, and report statistics.
 *
 * @package App\Queries\Labels
 */
final readonly class LabelAnalytics
{
    /**
     * Fetches a collection of analytical data for a given label.
     * This method aggregates various analytics (views, articles, contributors, reports) into a single collection, making it easy to retrieve all relevant statistics for a specific label.
     *
     * @param  Label $label The label for which to fetch analytics.
     * @return Collection<string, array<string, string>>
     */
    public function fetch(Label $label): Collection
    {
        return collect([
            'views' => $this->getViewAnalytics($label),
            'word' => $this->getArticleAnalytics($label),
            'contributor' => $this->getContributorAnalytics($label),
            'report' => $this->getReportAnalytics($label),
        ]);
    }

    /**
     * Retrieves article-related analytics for a given label.
     *
     * This method calculates the number of published articles associated with the label and compares it against the total number of published articles across the platform.
     * It returns human-readable statistics and a percentage for display.
     *
     * @param  Label $label The label for which to calculate article analytics.
     * @return array<string, string>
     */
    private function getArticleAnalytics(Label $label): array
    {
        $articles = $label->articles()->published()->count(); // Count published articles directly associated with this label
        $total = Article::query()->published()->count(); // Count all published articles in the system

        return [
            'statistic' => toHumanReadableNumber($articles),
            'altText' => trans(':percentage van het aantal woorden', [
                'percentage' => toHumanReadablePercentage($total, $articles),
            ]),
        ];
    }

    /**
     * Retrieves view-related analytics for a given label.
     *
     * This method sums up the total views for all published articles associated with the label and compares it against the total views of all published articles system-wide.
     * It returns human-readable statistics and a percentage for display.
     *
     * @param  Label $label The label for which to calculate view analytics.
     * @return array<string, string>
     */
    private function getViewAnalytics(Label $label): array
    {
        $views = (int) $label->articles()->published()->sum('views'); // Sum views for published articles directly associated with this label
        $totalViews = (int) Article::query()->published()->sum('views'); // Sum views for all published articles in the system

        return [
            'statistic' => toHumanReadableNumber($views),
            'altText' => trans(':percentage van de totale weergaves', [
                'percentage' => toHumanReadablePercentage($totalViews, $views),
            ]),
        ];
    }

    /**
     * Retrieves contributor-related analytics for a given label.
     *
     * This method counts the number of unique authors who have published articles associated with the given label.
     * It also provides the total number of published articles by these contributors under this label.
     *
     * @param  Label $label The label for which to calculate contributor analytics.
     * @return array<string, string>
     */
    private function getContributorAnalytics(Label $label): array
    {
        // Count published articles associated with this label that have an author
        $totalArticles = Article::query()
            ->published()
            ->whereHas('author')
            ->whereHas('labels', function ($labelQuery) use ($label): void {
                $labelQuery->where('labels.id', $label->id); // Ensure the article is linked to the specific label
            })->count();

        // Count unique users who have published articles (suggestions) associated with this label
        $totalUniqueAuthors = User::whereHas('suggestions', function ($suggestionQuery) use ($label): void {
            $suggestionQuery->whereNotNull('published_at');

            /** @phpstan-ignore-next-line */
            $suggestionQuery->whereHas('labels', function ($labelQuery) use ($label): void {
                $labelQuery->where('labels.id', $label->id);
            });
        })->count();

        return [
            'statistic' => toHumanReadableNumber($totalUniqueAuthors),
            'altText' => trans('Goed voor een :amount bijdrages', [
                'amount' => toHumanReadableNumber($totalArticles),
            ]),
        ];
    }

    /**
     * Retrieves report-related analytics for a given label.
     *
     * This method counts the number of article reports that are linked to published articles associated with the given label.
     * It also provides the total number of all article reports in the system for comparison.
     *
     * @param  Label $label The label for which to calculate report analytics.
     * @return array<string, string>
     */
    private function getReportAnalytics(Label $label): array
    {
        // Count article reports where the associated article is published and linked to this label
        $totalAttachedReports = ArticleReport::whereHas('article', function ($articleQuery) use ($label): void {
            $articleQuery->published();

            /** @phpstan-ignore-next-line */
            $articleQuery->whereHas('labels', function ($labelQuery) use ($label): void {
                $labelQuery->where('labels.id', $label->id);
            });
        })->count();

        $totalReports = ArticleReport::count(); // Count all article reports in the system

        return [
            'statistic' => toHumanReadableNumber($totalAttachedReports),
            'altText' => trans('Goed voor :percent van de meldingen', [
                'percent' => toHumanReadablePercentage($totalReports, $totalAttachedReports),
            ]),
        ];
    }
}
