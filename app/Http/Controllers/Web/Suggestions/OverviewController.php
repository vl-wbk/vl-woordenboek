<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Suggestions;

use App\QueryBuilders\UserSuggestionQueryBuilder;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * Class OverviewController
 *
 * This controller handles the display of user suggestions.
 * It retrieves suggestions specific to the authenticated user and renders them in a paginated view.
 *
 * @see tests/Feature/Http/Controllers/Web/Suggestions/OverviewControllerTest.php - Unit tests that backs the controller
 */
final readonly class OverviewController
{
    /**
     * Handles the request to display the overview of user suggestions.
     * This method retrieves the user's suggestions using the UserSuggestionQueryBuilder, paginates the results, and passes them to the 'suggestions.index' view for rendering.
     *
     * @param  Request $request  The incoming HTTP request.
     * @return Renderable        The rendered view of user suggestions.
     */
    #[Get(uri: 'mijn-suggesties', name: 'suggestions:index', middleware: ['auth', 'forbid-banned-user'])]
    public function __invoke(Request $request): Renderable
    {
        $suggestionQuery = new UserSuggestionQueryBuilder($request);

        return view('suggestions.index', [
            'results' => $suggestionQuery->paginate()->appends(request()->query())
        ]);
    }
}
