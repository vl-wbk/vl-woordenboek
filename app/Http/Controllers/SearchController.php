<?php

namespace App\Http\Controllers;

use App\Queries\SearchWordQuery;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

final readonly class SearchController
{
    public function __invoke(Request $request, SearchWordQuery $searchWordQuery): Renderable
    {
        return view('welcome', [
            'results' => $searchWordQuery->execute($request->get('zoekterm'))
        ]);
    }
}
