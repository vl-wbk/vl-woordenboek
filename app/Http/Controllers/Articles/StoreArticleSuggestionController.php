<?php

declare(strict_types=1);

namespace App\Http\Controllers\Articles;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Http\Controllers\SearchController;
use App\Http\Requests\Articles\StoreSuggestionRequest;
use App\Models\Region;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

final readonly class StoreArticleSuggestionController
{
    public function create(): Renderable
    {
        return view('definitions.create', [
            'regions' => Region::query()->pluck('name', 'id')
        ]);
    }

    public function store(StoreSuggestionRequest $storeSuggestionRequest, StoreArticleSuggestion $storeArticleSuggestion): RedirectResponse
    {
        $storeArticleSuggestion->execute($storeSuggestionRequest->getData());

        return redirect()->action(SearchController::class);
    }
}
