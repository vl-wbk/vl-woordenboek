<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * Controller for serving random and "word of the day" dictionary articles.
 *
 * This controller provides endpoints for displaying a random published dictionary article
 * and the current "word of the day" article. Both endpoints increment the view count
 * for the selected article and pass the article and its relevant etymology count to the view.
 *
 * Endpoints:
 * - GET /willekeurig-woordenboek-artikel: Returns a random published article.
 * - GET /woord-van-de-dag: Returns the article marked as "word of the day".
 *
 * For each article, only etymologies that are not in Draft, Rejected, or Archived status are counted.
 *
 * Usage:
 * - Intended for public-facing routes to display dictionary content.
 * - Ensures articles are tracked for popularity via view counts.
 *
 * @package App\Http\Controllers\Web\Articles
 */
final readonly class RandomDictionaryArticleController
{
    /**
     * Handles the request for a ranbdom published dictionary article.
     *
     * Selects a random that has been published, increments its view count,
     * and returns the article and the its valid etymology count to the definitions.show view.
     *
     * @return Renderable The rendered view with article and etymology data.
     */
    #[Get(uri: '/willekeurig-woordenboek-artikel', name: 'word-information.random')]
    public function __invoke(): Renderable
    {
        $article = Article::whereNotNull('published_at')->inRandomOrder()->first();
        $article->increment('views', 1);

        return view('definitions.show', data: [
            'word' => $article,
            'etymologies' => $article->etymology()->whereNotIn('status', [EtymologyStatus::Draft, EtymologyStatus::Rejected, EtymologyStatus::Archived])->count(),
        ]);
    }

    /**
     * Handles the request for the "word of the day" article.
     *
     * Selects the article flagged as "word of the day", increments its view count,
     * and returns the article and its valid etymology count to the definitions.show view.
     *
     * @return Renderable The rendered view with article and etymology data.
     */
    #[Get(uri: '/woord-van-de-dag', name: 'word-information.wtod')]
    public function wtod(): Renderable
    {
        $article = Article::where('wotd', true)->firstOrFail();
        $article->increment('views');

        return view('definitions.show', data: [
            'word' => $article,
            'etymologies' => $article->etymology()->whereNotIn('status', [EtymologyStatus::Draft, EtymologyStatus::Rejected, EtymologyStatus::Archived])->count(),
        ]);
    }
}
