<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\Request;

final readonly class OverviewController
{
    #[Get(uri: '/mijn-concepten', name: 'concepts:index', middleware: ['auth', 'forbid-banned-user'])]
    public function __invoke(Request $request): Renderable
    {
        return view('concepts.index', data: [
            'user' => $request->user(),
            'concepts' => $request->user()->concepts()->paginate(5),
        ]);
    }
}
