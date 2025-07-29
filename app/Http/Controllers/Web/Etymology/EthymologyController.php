<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Etymology;

use App\Actions\Articles\StoreEtymologySubmission;
use App\Concerns\RateLimitSubmission;
use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use App\Http\Requests\Articles\StoreEtymologyRequest;
use App\Models\Article;
use App\Models\Etymology;
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

    #[Get(uri: 'ethymology/{etymology}', name: 'etymology:show')]
    public function show(Etymology $etymology): Renderable
    {
        return view('definitions.etymology.show', data: [
            'etymology' => $etymology,
            'etymologies' => $etymology->article
                ->etymology()
                ->whereNotIn('status', [EtymologyStatus::Draft, EtymologyStatus::Rejected, EtymologyStatus::Archived])
                ->get(),
        ]);
    }

    #[Get(uri: 'etymologie/{article}/nieuwe-suggestie', name: 'etymology:create')]
    public function create(Article $article): Renderable
    {
        return view('definitions.etymology.create', data: [
            'article' => $article,
            'types' => EtymologyTypes::cases(),
        ]);
    }

    #[Post(uri: 'etymologie/{article}/nieuwe-suggestie', name: 'etymology:store')]
    public function store(StoreEtymologyRequest $storeEtymologyRequest, Article $article, StoreEtymologySubmission $storeEtymologySubmission): RedirectResponse|Closure
    {
        return $this->attemptSubmissionWithRateLimiting($storeEtymologyRequest, 'etymologySubmission', function () use ($article, $storeEtymologyRequest, $storeEtymologySubmission): RedirectResponse {
            $etymology = $storeEtymologySubmission->execute(article: $article, etymologySubmissionData: $storeEtymologyRequest->getData());

            return redirect()->route('etymology:create', $etymology);
        });
    }
}
