<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Statistics;

use App\Enums\ArticleStates;
use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Services\StatisticService;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final class DictionaryStatistics extends Controller
{
    #[Get(uri: 'statistieken', name: 'statistics')]
    public function __invoke(): Renderable
    {
        $statistics = new StatisticService();

        return view('statistics.index', [
            'metrics'            => $statistics->getMetrics(),
            'articleCount' => $statistics->getArticleCount(),
            'userRegistrations' => $statistics->userRegistrationChartData(),
            'articleChart' => $statistics->articleChartData(),
            'targetArticleCount' => 40000,

            // Counters for the data bar
            'publishedCount' => Article::where('state', ArticleStates::Published)->count(),
            'reviewCount' => Article::where('state', ArticleStates::Approval)->count(),
            'newCount' => Article::where('state', ArticleStates::New)->count(),
            'draftCount' => Article::where('state', ArticleStates::Draft)->count(),
            'archivedCount' => Article::where('state', ArticleStates::Archived)->count(),
            'externalCount' => Article::where('state', ArticleStates::ExternalData)->count(),
            'rejectedCount' => Article::where('state', ArticleStates::RejectedPublication)->count(),

            // Recent activity
            'recentArticles' => Article::query()->with(['partOfSpeech'])->published()->latest()->take(10)->get(),

        ]);
    }
}
