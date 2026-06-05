<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Actions\Concepts\EditSuggestionConcept;
use App\Http\Requests\Articles\StoreConceptRequest;
use App\Models\Concept;
use App\Models\PartOfSpeech;
use App\Models\Region;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Patch;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

final readonly class EditController
{
    use AuthorizesRequests;

    #[Get(uri: 'concept/{concept}', name: 'concepts:edit', middleware: ['auth', 'verified'])]
    public function edit(Request $request, Concept $concept): Renderable
    {
        $this->authorize('update',$concept);

        return view('concepts.edit', data: [
            'user' => $request->user(),
            'concept' => $concept,
            'regions' => Region::query()->pluck('name', 'id'),
            'partOfSpeeches' => PartOfSpeech::query()->where('suggestible', true)->pluck('name', 'id'),
        ]);
    }

    /**
     * @throws InvalidDataClass  when the Data Transfer Object contains invalid data.
     * @throws Throwable         when the storage action couldn't not perform successfully.
     */
    #[Patch(uri: 'concept/{concept}', name: 'concepts:update', middleware: ['auth', 'verified'])]
    public function update(StoreConceptRequest $storeConceptRequest, Concept $concept): RedirectResponse
    {
        return match ($storeConceptRequest->getSubmissionAction()) {
            'save' => $this->handleConceptUpdate($storeConceptRequest, $concept),
            'submit' => $this->handleStoreSubmission($storeConceptRequest, $concept),
            default => abort(400, __('Onbekende handeling')),
        };
    }

    /**
     * @throws InvalidDataClass  when the data transfer object contains invalid data.
     * @throws Throwable         when the action that is responsible for updating the concept couldn't complete
     */
    private function handleConceptUpdate(StoreConceptRequest $storeConceptRequest, Concept $concept): RedirectResponse
    {
        EditSuggestionConcept::execute($storeConceptRequest, $concept);
        flash(text: 'De aanpassingen aan uw concept zijn opgeslagen', class: 'alert-success');

        return redirect()->route('concepts:edit', $concept);
    }

    /**
     * @throws InvalidDataClass  when the Data Transfer Object contains invalid data.
     * @throws Throwable         when the storage action couldn't not perform successfully.
     */
    private function handleStoreSubmission(StoreConceptRequest $storeConceptRequest, Concept $concept): RedirectResponse
    {
        $suggestion = (new StoreArticleSuggestion())->execute($storeConceptRequest->getData(), $concept);

        return redirect()->route('suggestions:show', $suggestion);
    }
}
