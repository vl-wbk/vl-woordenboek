<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\StoreArticleSuggestion;
use App\Http\Controllers\SearchController;
use App\Http\Requests\Articles\StoreSuggestionRequest;
use App\Models\Region;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Honeypot\ProtectAgainstSpam;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

final readonly class StoreArticleSuggestionController
{
    #[Get(uri: 'woordenboek-artikel/insturen', name: 'definitions.create')]
    public function create(): Renderable
    {
        return view('definitions.create', [
            'regions' => Region::query()->pluck('name', 'id')
        ]);
    }

    #[Post(uri: 'woordenboek-artikel/insturen', name: 'definitions.store')]
    public function store(StoreSuggestionRequest $storeSuggestionRequest, StoreArticleSuggestion $storeArticleSuggestion): RedirectResponse
    {
        $storeArticleSuggestion->execute($storeSuggestionRequest->getData());

        return redirect()->action(SearchController::class);
    }
}
