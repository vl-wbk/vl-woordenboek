<?php

declare(strict_types=1);

namespace App\Queries\Labels;

use App\Models\Article;
use App\Models\ArticleReport;
use App\Models\Label;
use App\Models\User;
use Illuminate\Support\Collection;

final readonly class LabelAnalytics
{
    public function fetch(Label $label): Collection
    {
        return collect([
            'views' => $this->getViewAnalytics($label),
            'word' => $this->getArticleAnalytics($label),
            'contributor' => $this->getContributorAnalytics($label),
            'report' => $this->getReportAnalytics($label),
        ]);
    }

    private function getArticleAnalytics(Label $label): array
    {
        $articles = $label->articles()->whereNotNull('published_at')->count();
        $total = Article::query()->whereNotNull('published_at')->count();

        return [
            'statistic' => toHumanReadableNumber($articles),
            'altText' => trans(':percentage van het aantal woorden', [
                'percentage' => toHumanReadablePercentage($total, $articles),
            ]),
        ];
    }

    private function getViewAnalytics(Label $label): array
    {
        $views = (int) $label->articles()->whereNotNull('published_at')->sum('views');
        $totalViews = (int) Article::query()->whereNotNull('published_at')->sum('views');

        return [
            'statistic' => toHumanReadableNumber($views),
            'altText' => trans(':percentage van de totale weergaves', [
                'percentage' => toHumanReadablePercentage($totalViews, $views),
            ]),
        ];
    }

    private function getContributorAnalytics(Label $label): array
    {
        $totalArticles = Article::query()
            ->whereNotNull('published_at')
            ->whereHas('author')
            ->whereHas('labels', function ($labelQuery) use ($label) {
                // Ensure the suggestion is linked to the specific label
                $labelQuery->where('labels.id', $label->id);
            })->count();

        $totalUniqueAuthors = User::whereHas('suggestions', function ($suggestionQuery) use ($label) {
            $suggestionQuery->whereNotNull('published_at');
            $suggestionQuery->whereHas('labels', function ($labelQuery) use ($label) {
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

    private function getReportAnalytics(Label $label): array
    {
        $totalAttachedReports = ArticleReport::whereHas('article', function ($articleQuery) use ($label) {
            $articleQuery->whereNotNull('published_at');
            $articleQuery->whereHas('labels', function ($labelQuery) use ($label) {
                $labelQuery->where('labels.id', $label->id);
            });
        })->count();

        $totalReports = ArticleReport::count();

        return [
            'statistic' => toHumanReadableNumber($totalAttachedReports),
            'altText' => trans('Goed voor :percent van de meldingen', [
                'percent' => toHumanReadablePercentage($totalReports, $totalAttachedReports),
            ])
        ];
    }
}
