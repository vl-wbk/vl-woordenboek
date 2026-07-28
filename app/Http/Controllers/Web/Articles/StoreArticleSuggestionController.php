<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Concerns\RateLimitSubmission;
use App\Http\Requests\Articles\StoreSuggestionRequest;
use App\Models\PartOfSpeech;
use App\Models\Region;
use App\Services\SuggestionQuotaService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Routing\Attributes\Controllers\Middleware;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Throwable;

/**
 * StoreArticleSuggestionController manages the creation of new dictionairy article suggestion entries.
 *
 * This controller handles both the display of the submîssion form and the processing of new article submissions.
 * It integrates the attribute-based routing system for clean route definitions.
 * The workflow supports a streamlines proces for contributring new words to the Vlaams Woordenboek
 *
 * @package App\Http\Controllers\Web\Articles
 */
final class StoreArticleSuggestionController
{
    /**
     * Displays the article submission form.
     *
     * Prepares the creation view by loading all available regions and parts of speech for dropdown selection.
     * The regions and parts of speech are provided in a format suitable for form select elements, with their names as labels and IDs as values.
     *
     * @return Renderable The form view for creating new dictionary entries.
     */
    #[Get(uri: 'woordenboek-artikelen/insturen', name: 'definitions.create')]
    public function create(SuggestionQuotaService $suggestionQuotaService): Renderable
    {
        return view('definitions.create', [
            'regions' => Region::query()->pluck('name', 'id'),
            'resterend' => $suggestionQuotaService->remaining(request()),
            'partOfSpeeches' => PartOfSpeech::query()->where('suggestible', true)->pluck('name', 'id'),
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
     *
     * @throws Throwable        when the suggestion couldn't be stored successfully in the database
     * @throws InvalidDataClass when the data transfer object couldn't be found in the application.
     */
    #[Post(uri: 'woordenboek-artikelen/insturen', name: 'definitions.store', middleware: ['throttle:suggestions', 'suggestion.quotum'])]
    public function store(StoreSuggestionRequest $storeSuggestionRequest, StoreArticleSuggestion $storeArticleSuggestion): RedirectResponse
    {
        $storeArticleSuggestion->execute(suggestionData: $storeSuggestionRequest->getData());

        return redirect()->route('definitions.create');
    }
}
