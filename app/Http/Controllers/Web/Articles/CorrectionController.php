<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\StoreArticleCorrection;
use App\Http\Requests\Articles\ArticleCorrectRequest;
use App\Models\Article;
use App\Models\PartOfSpeech;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Throwable;

final readonly class CorrectionController
{
    #[Get(uri: '/woordenboek-artikel/{article}/correctie', name: 'correction:create', middleware: ['auth', 'forbid-banned-user'])]
    #[Authorize(ability: 'create', models: 'App\Models\CorrectionProposal')]
    public function create(Article $article): Renderable
    {
        return view('corrections.create', data: [
            'word' => $article->load(['regions', 'labels', 'related.partOfSpeech']),
            'partOfSpeeches' => PartOfSpeech::query()->where('suggestible', true)->pluck('name', 'id')
        ]);
    }

    /**
     * @throws Throwable
     * @throws InvalidDataClass
     */
    #[Post(uri: '/woordenboek-artikel/{article}/correctie', name: 'correction:store', middleware: ['auth', 'forbid-banned-user'])]
    #[Authorize(ability: 'create', models: 'App\Models\CorrectionProposal')]
    public function store(Article $article, ArticleCorrectRequest $articleCorrectRequest, StoreArticleCorrection $storeArticleCorrection): RedirectResponse
    {
        $storeArticleCorrection($article, $articleCorrectRequest->getData());

        return back();
    }
}
