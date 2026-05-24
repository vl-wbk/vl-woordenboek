<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Etymology;

use App\Actions\Articles\StoreEtymologySubmission;
use App\Attributes\Todo;
use App\Concerns\RateLimitSubmission;
use App\Enums\Articles\EtymologySources;
use App\Http\Requests\Articles\StoreEtymologyRequest;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\LaravelData\Exceptions\InvalidDataClass;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;
use Throwable;

#[Todo(message: 'Provide docblocks for this class and their methods', priority: 'low')]
final class EthymologyController
{
    use RateLimitSubmission;

    #[Get(uri: 'etymologie/{article}/nieuwe-suggestie', name: 'etymology:create')]
    public function create(Article $article): Renderable
    {
        return view('definitions.etymology.create', data: [
            'article' => $article,
            'sources' => EtymologySources::cases(),
        ]);
    }

    /**
     * @throws Throwable        when etymology record couldn't be stored in the application
     * @throws InvalidDataClass when the given data transfer object is invalid
     */
    #[Post(uri: 'etymologie/{article}/nieuwe-suggestie', name: 'etymology:store')]
    public function store(StoreEtymologyRequest $storeEtymologyRequest, Article $article, StoreEtymologySubmission $storeEtymologySubmission): RedirectResponse
    {
        $this->throttleSubmission($storeEtymologyRequest, 'etymologySubmission', function () use ($article, $storeEtymologyRequest, $storeEtymologySubmission): void {
            $etymology = $storeEtymologySubmission->execute(article: $article, etymologySubmissionData: $storeEtymologyRequest->getData());
        });

        return redirect()->route('etymology:create', $article);
    }
}
