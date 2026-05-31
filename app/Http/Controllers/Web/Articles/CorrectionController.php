<?php 

declare(strict_types=1); 

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\StoreArticleCorrection;
use App\Features\ArticleCorrectionsFeature;
use App\Features\BetaProgramFeature;
use App\Http\Requests\Articles\ArticleCorrectRequest;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Laravel\Pennant\Feature;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class CorrectionController 
{
    #[Get(uri: '/woordenboek-artikel/{article}/correctie', name: 'correction:create', middleware: ['auth', 'forbid-banned-user'])]
    #[Authorize(ability: 'create', models: 'App\Models\CorrectionProposal')]
    public function create(Request $request, Article $article): Renderable
    {
        return view('corrections.create', data: [
            'word' => $article->load(['regions', 'labels', 'related.partOfSpeech'])
        ]);
    }

    #[Post(uri: '/woordenboek-artikel/{article}/correctie', name: 'correction:store', middleware: ['auth', 'forbid-banned-user'])]
    #[Authorize(ability: 'create', models: 'App\Models\CorrectionProposal')]
    public function store(Article $article, ArticleCorrectRequest $articleCorrectRequest, StoreArticleCorrection $storeArticleCorrection): RedirectResponse
    {
        $storeArticleCorrection($article, $articleCorrectRequest->getData());
        flash('We hebben je correctie goed ontvangen! We modereren deze zo spoedig mogelijk.', 'alert-success');

        return back();
    }
}