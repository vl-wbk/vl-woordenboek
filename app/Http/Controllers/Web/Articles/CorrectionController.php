<?php 

declare(strict_types=1); 

namespace App\Http\Controllers\Web\Articles;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class CorrectionController 
{
    #[Get(uri: '/woordenboek-artikel/{article}/correctie', name: 'correction:create', middleware: ['auth', 'forbid-banned-user'])]
    public function create(Article $article): Renderable
    {
        return view('corrections.create', data: [
            'word' => $article
        ]);
    }
}