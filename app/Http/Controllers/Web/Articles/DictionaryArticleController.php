<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Article;
use App\Policies\ArticlePolicy;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * DictionaryArticleController displays individual dictionary entries.
 *
 * This invokable controller handles the display of detailed information for specific dictionary entries in the Vlaams Woordenboek.
 * It leverages Laravel's route model binding to automatically fetch the requested article based on the URL parameter.
 *
 * @package App\Http\Controllers\Web\Articles
 */
final readonly class DictionaryArticleController
{
    use AuthorizesRequests;

    /**
     * Displays a single dictionary entry.
     *
     * This method renders the detailed view for a specific word entry, showing its definition, usage examples, and regional information.
     * Route model binding automatically resolves the {word} parameter to a full Article model instance.
     *
     * @param Article $word The dictionary entry to display
     * @return Renderable     The view containing article details
     */
    #[Get(uri: '/woordenboek-artikel/{word}', name: 'word-information.show')]
    public function __invoke(Request $request, Article $word): Renderable|RedirectResponse
    {
        if (Gate::allows(ArticlePolicy::DisplayArticle, $word)) {
            $word->increment('views', 1); // Increment the view counter for thearticle by one. Because the user decided to view the article.

            return view('definitions.show', data: [
                'word' => $word,
                'etymologies' => $word->etymologies()->whereNotIn('status', [EtymologyStatus::Draft, EtymologyStatus::Rejected, EtymologyStatus::Archived])->get(),
            ]);
        }

        flash('Het artikel is momenteel in onderhoud. Kom later nog eens terug');
        return redirect()->route('search.results');
    }
}
