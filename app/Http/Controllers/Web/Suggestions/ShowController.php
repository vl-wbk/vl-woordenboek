<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Suggestions;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ShowController
{
    use AuthorizesRequests;

    #[Get(uri: '/suggestie/{article}', name: 'suggestions:show', middleware: ['auth', 'verified', 'forbid-banned-user'])]
    public function __invoke(Request $request, Article $article): Renderable
    {
        $this->authorize('view-suggestion', $article);

        return view('suggestions.show', data: [
            'user' => $request->user(),
            'article' => $article,
        ]);
    }
}
