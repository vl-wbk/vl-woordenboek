<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Actions\Concepts\StoreSuggestionConcept;
use App\Concerns\RateLimitSubmission;
use App\Data\SuggestionData;
use App\Http\Requests\Articles\StoreConceptRequest;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Get;
use App\Models\Region;
use App\Models\PartOfSpeech;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

final readonly class CreateController
{
    use RateLimitSubmission;

    #[Get(uri: 'nieuw-concept', name: 'concepts:create', middleware: ['auth', 'forbid-banned-user'])]
    public function create(Request $request): Renderable
    {
        return view('concepts.create', data: [
            'user' => $request->user(),
            'regions' => Region::query()->pluck('name', 'id'),
            'partOfSpeeches' => PartOfSpeech::query()->where('suggestible', true)->pluck('name', 'id'),
        ]);
    }

    /**
     * @throws InvalidDataClass  when the data transfer object contains invalid data
     * @throws Throwable         when the submission action couldn't be stored successfully.
     */
    #[Post(uri: 'concept-opslaan', name: 'concepts:store', middleware: ['auth', 'forbid-banned-user'])]
    public function store(StoreConceptRequest $storeConceptRequest): RedirectResponse
    {
        return match ($storeConceptRequest->input('action')) {
            'save' => $this->handleConceptCreation($storeConceptRequest->getData()),
            'submit' => $this->handleStoreSubmission($storeConceptRequest),
            default => abort(400, __('Onbekende handeling')),
        };
    }

    /**
     * @throws Throwable When the concept couldn't be stored successfully in the application.
     */
    protected function handleConceptCreation(SuggestionData $suggestionData): RedirectResponse
    {
        $concept = StoreSuggestionConcept::execute($suggestionData);
        flash('We het concept van de suggestie succesvol opgeslagen', 'alert-success');

        return redirect()->route('concepts:edit', $concept); // Redirect to the previous view.
    }

    /**
     * @throws InvalidDataClass when the data transfer object contains invalid data.
     * @throws Throwable        when the submission action couldn't perform successfully
     */
    protected function handleStoreSubmission(StoreConceptRequest $storeConceptRequest): RedirectResponse
    {
        $submission = $this->throttleSubmission($storeConceptRequest, 'suggestion', function () use ($storeConceptRequest): Article {
            return (new StoreArticleSuggestion())->execute(suggestionData: $storeConceptRequest->getData());
        });

        return redirect()->route('suggestions:show', $submission);
    }
}
