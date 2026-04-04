<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Concerns\RateLimitSubmission;
use App\Data\SuggestionData;
use App\Http\Requests\Articles\StoreConceptRequest;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use App\Models\Region;
use App\Models\PartOfSpeech;
use Spatie\RouteAttributes\Attributes\Post;
use Symfony\Component\HttpFoundation\RedirectResponse;

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

    #[Post(uri: 'concept-opslaan', name: 'concepts:store', middleware: ['auth', 'forbid-banned-user'])]
    public function store(StoreConceptRequest $storeConceptRequest): RedirectResponse
    {
        return match ($storeConceptRequest->input('action')) {
            'save' => $this->handleConceptCreation($storeConceptRequest->getData()),
            'submit' => $this->handleStoreSubmission($storeConceptRequest),
            default => abort(400, __('Onbekende handeling')),
        };
    }

    protected function handleConceptCreation(SuggestionData $suggestionData): RedirectResponse
    {
        $concept =
        flash('We het concept van de suggestie succesvol opgeslagen', 'alert-success');

        return redirect()->route('concepts:edit', $concept); // Redirect to the previous view.
    }

    protected function handleStoreSubmission(StoreConceptRequest $storeConceptRequest): RedirectResponse
    {
        $submission = $this->throttleSubmission($storeConceptRequest, 'suggestion', function () use ($storeConceptRequest): Article {
            return (new StoreArticleSuggestion())->execute(suggestionData: $storeConceptRequest->getData());
        });

        return redirect()->route('suggestions:show', $submission);
    }
}
