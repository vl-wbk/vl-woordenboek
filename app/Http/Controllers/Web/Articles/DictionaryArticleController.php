<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\ExampleSentenceStatus;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use App\Models\WordOfTheDay;
use App\Policies\ArticlePolicy;
use App\States\ExampleSentence\Approved;
use App\States\Reporting\Status;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
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
     * Redirects legacy slug-based URLs.
     */
    #[Get(uri: '/definities/term/{slug}')]
    public function redirectOldTerm(string $slug): RedirectResponse
    {
        $article = Article::where('word', $slug)->first();

        return $article
            ? to_route('word-information.show', $article, status: 301)
            : to_route('search.results');
    }

    /**
     * Redirects legacy ID-based URLs.
     */
    #[Get(uri: '/definities/{article}')]
    public function redirectOldId(Article $article): RedirectResponse
    {
        return to_route('word-information.show', ['word' => $article], status: 301);
    }

    /**
     * Displays a single dictionary entry.
     *
     * This method renders the detailed view for a specific word entry, showing its definition, usage examples, and regional information.
     * Route model binding automatically resolves the {word} parameter to a full Article model instance.
     *
     * @param  Article $word The dictionary entry to display
     * @return Renderable|RedirectResponse The view containing article details
     */
    #[Get(uri: '/woordenboek-artikel/{word}', name: 'word-information.show')]
    public function __invoke(Article $word): Renderable|RedirectResponse
    {
        if (Gate::allows(ArticlePolicy::DisplayArticle, $word)) {
            $word->recordView(); // Increment the view counter for thearticle by one. Because the user decided to view the article.

            return view('definitions.show', data: [
                'word' => $word->loadCount([
                    'reports' => fn (Builder $query) => $query->where('state', Status::Open)->orWhere('state', Status::InProgress),
                    'notes',
                    'audits'
                ]),
                'exampleCount' => $word->userExamples()->whereState('status', Approved::class)->count(),
                'articleResource' => ArticleResource::class,
                'etymologies' => $word->etymologies()->whereNotIn('status', [EtymologyStatus::Draft, EtymologyStatus::Rejected, EtymologyStatus::Archived])->get(),
                'upcomingSchedule' => WordOfTheDay::where('article_id', $word->id)->whereDate('scheduled_for', today())->first(),
            ]);
        }

        flash('Het artikel is momenteel in onderhoud. Kom later nog eens terug');
        return redirect()->route('search.results');
    }
}
