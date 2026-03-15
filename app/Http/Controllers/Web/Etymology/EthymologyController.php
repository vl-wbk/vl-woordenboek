<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Etymology;

use App\Actions\Articles\StoreEtymologySubmission;
use App\Actions\Support\StoreFeedbackSubmission;
use App\Concerns\RateLimitSubmission;
use App\Enums\Articles\EtymologySources;
use App\Http\Requests\Articles\StoreEtymologyRequest;
use App\Http\Requests\Support\StoreFeedbackRequest;
use App\Models\Article;
use Closure;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

/**
 * @todo add docblocks for this class
 */
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

    #[Post(uri: 'etymologie/{article}/nieuwe-suggestie', name: 'etymology:store')]
    public function store(StoreEtymologyRequest $storeEtymologyRequest, Article $article, StoreEtymologySubmission $storeEtymologySubmission): RedirectResponse|Closure
    {
        $this->throttleSubmission($storeEtymologyRequest, 'etymologySubmission', function () use ($article, $storeEtymologyRequest, $storeEtymologySubmission): void {
            $etymology = $storeEtymologySubmission->execute(article: $article, etymologySubmissionData: $storeEtymologyRequest->getData());
        });

        return redirect()->route('etymology:create', $article);
    }
}
