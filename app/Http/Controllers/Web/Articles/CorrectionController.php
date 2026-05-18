<?php 

declare(strict_types=1); 

namespace App\Http\Controllers\Web\Articles;

use App\Features\ArticleCorrectionsFeature;
use App\Features\BetaProgramFeature;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Laravel\Pennant\Feature;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CorrectionController 
{
    #[Get(uri: '/woordenboek-artikel/{article}/correctie', name: 'correction:create', middleware: ['auth', 'forbid-banned-user'])]
    #[Authorize(ability: 'create', models: 'App\Models\CorrectionProposal')]
    public function create(Request $request, Article $article): Renderable
    {

        return view('corrections.create', data: [
            'word' => $article
        ]);
    }
}