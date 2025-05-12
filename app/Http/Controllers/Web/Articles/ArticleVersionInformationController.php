<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use Illuminate\Contracts\Support\Renderable;
use OwenIt\Auditing\Models\Audit;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ArticleVersionInformationController
{
    #[Get(uri: '/versie-informatie/{audit}', name: 'change:information', middleware: ['auth', 'forbid-banned-user', 'verified'])]
    public function __invoke(Audit $audit): Renderable
    {
        return view('versions.info', data: [
            'audit' => $audit
        ]);
    }
}
