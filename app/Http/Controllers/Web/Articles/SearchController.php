<?php

namespace App\Http\Controllers\Web\Articles;

use App\Queries\SearchWordQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class SearchController
{
    #[Get(uri: '/', name: 'home')]
    #[Get(uri: '/resultaten', name: 'search.results')]
    public function __invoke(Request $request, SearchWordQuery $searchWordQuery): Renderable
    {
        return view('welcome', [
            'results' => $searchWordQuery->execute($request->get('zoekterm'))
        ]);
    }
}
