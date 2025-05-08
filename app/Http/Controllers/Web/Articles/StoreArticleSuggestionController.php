<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Concerns\RateLimitSubmission;
use App\Http\Requests\Articles\StoreSuggestionRequest;
use App\Models\PartOfSpeech;
use App\Models\Region;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\RateLimiter;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * StoreArticleSuggestionController manages the creation of new dictionairy article suggestion entries.
 *
 * This controller handles both the display of the submîssion form and the processing of new article submissions.
 * It integrates the attribute-based routing system for clean route definitions.
 * The workflow supports a streamlines proces for contributring new words to the Vlaams Woordenboek
 *
 * @package App\Http\Controllers\Web\Articles
 */
#[Middleware(middleware: ['throttle:global'])]
final class StoreArticleSuggestionController
{
    use RateLimitSubmission;

    /**
     * Displays the article submission form.
     *
     * Prepares the creation view by loading all available regions and parts of speech for dropdown selection.
     * The regions and parts of speech are provided in a format suitable for form select elements, with their names as labels and IDs as values.
     *
     * @return Renderable The form view for creating new dictionary entries.
     */
    #[Get(uri: 'woordenboek-artikelen/insturen', name: 'definitions.create')]
    public function create(): Renderable
    {
        return view('definitions.create', [
            'regions' => Region::query()->pluck('name', 'id'),
            'partOfSpeeches' => PartOfSpeech::query()->pluck('name', 'id'),
        ]);
    }

    /**
     * Processes the submission of a new dictionary entry.
     *
     * Handles the POST request containing the new article data.
     * After validation through the form request, it delegates the storage operation to a dedicated action class.
     * Upon successful creation, redirects to the search interface where users can find their newly submitted entry.
     *
     * @param  StoreSuggestionRequest $storeSuggestionRequest   The form request that validates the request data?
     * @param  StoreArticleSuggestion $storeArticleSuggestion   The action that uis responsible for storing the dictionary article.
     * @return RedirectResponse                                 Redirects to search interface after submission.
     */
    #[Post(uri: 'woordenboek-artikelen/insturen', name: 'definitions.store')]
    public function store(StoreSuggestionRequest $storeSuggestionRequest, StoreArticleSuggestion $storeArticleSuggestion): RedirectResponse
    {
        return $this->attemptSubmissionWithRateLimiting($storeSuggestionRequest, 'submission', function () use ($storeArticleSuggestion, $storeSuggestionRequest): RedirectResponse {
            $storeArticleSuggestion->execute($storeSuggestionRequest->getData());
            flash('We hebben uw suggestie goed ontvangen. Onze redactie zal er spoedig naar kijken.', 'alert-success');

            return redirect()->action([StoreArticleSuggestionController::class, 'create']);
        });
    }
}
