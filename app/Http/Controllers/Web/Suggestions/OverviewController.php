<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Suggestions;

use App\QueryBuilders\UserSuggestionQueryBuilder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class OverviewController
{
    #[Get(uri: 'mijn-suggesties', name: 'suggestions:index', middleware: ['auth', 'forbid-banned-user'])]
    public function __invoke(Request $request): Renderable
    {
        $suggestionQuery = new UserSuggestionQueryBuilder($request);

        return view('suggestions.index', [
            'results' => $suggestionQuery->paginate()->appends(request()->query())
        ]);
    }
}
