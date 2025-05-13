<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\UserTypes;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OwenIt\Auditing\Models\Audit;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ArticleVersionInformationController
{
    #[Get(uri: '/versie-informatie/{audit}', name: 'change:information', middleware: ['auth', 'forbid-banned-user', 'verified'])]
    public function __invoke(Request $request Audit $audit): Renderable
    {
        abort_if($request->user()->user_type->isNot(UserTypes::Normal), Response::HTTP_NOT_FOUND);

        return view('versions.info', data: [
            'audit' => $audit
        ]);
    }
}
