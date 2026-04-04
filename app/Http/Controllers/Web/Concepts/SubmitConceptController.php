<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use App\Actions\Concepts\SubmitConceptAsSuggestion;
use App\Models\Concept;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\RedirectResponse;

final readonly class SubmitConceptController
{
    use AuthorizesRequests;

    #[Get(uri: 'concepten/{concept}/insturen', name: 'concepts:submit', middleware: ['auth', 'forbid-banned-user'])]
    public function __invoke(Concept $concept, SubmitConceptAsSuggestion $submitConceptAsSuggestion): RedirectResponse
    {
        $this->authorize('submit-concept', $concept);

        $submission = $submitConceptAsSuggestion->handle($concept);
        flash(text: 'We hebben het concept met success omgezet naar een suggestie in het Woordenboek', class: 'alert-success');

        return redirect()->route('suggestions:show', $submission);
    }
}
