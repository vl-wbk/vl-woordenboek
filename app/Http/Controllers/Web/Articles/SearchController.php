<?php

namespace App\Http\Controllers\Web\Articles;

use App\Models\Article;
use App\Queries\SearchWordQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * SearchController provides the dictionary search functionality.
 *
 * This invokable controller handles both the initial landing page and search results display for the Vlaams Woordenboek.
 * It uses attribute-based routing to serve both the homepage and search results through the same action, providing a seamless search experience.
 *
 * @package App\Http\Controllers\Web\Articles
 */
final readonly class SearchController
{
    /**
     * Handles both the homepage display and search results processing.
     *
     * When accessed without search terms, displays the homepage.
     * When a search term is provided through the 'zoekterm' parameter, executes the search query and displays matching results.
     * The view handles both empty and populated result sets appropriately.
     *
     * @param  Request $request                  The incoming HTTP request
     * @param  SearchWordQuery $searchWordQuery  The search query service
     * @return Renderable                        The view with optional search results
     */
    #[Get(uri: '/', name: 'home')]
    #[Get(uri: '/resultaten', name: 'search.results')]
    public function __invoke(Request $request, SearchWordQuery $searchWordQuery): Renderable
    {
        return view('welcome', [
            'articleCount' => Article::query()->count(),
            'results' => $searchWordQuery->execute($request->get('zoekterm')),
            'termPresent' => $request->has('zoekterm'),
        ]);
    }
}
