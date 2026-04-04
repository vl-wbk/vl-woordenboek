<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;
use App\Models\Region;
use App\Models\PartOfSpeech;

final readonly class CreateController
{
    #[Get(uri: 'nieuw-concept', name: 'concepts:create', middleware: ['auth', 'forbid-banned-user'])]
    public function create(Request $request): Renderable
    {
        return view('concepts.create', data: [
            'user' => $request->user(),
            'regions' => Region::query()->pluck('name', 'id'),
            'partOfSpeeches' => PartOfSpeech::query()->where('suggestible', true)->pluck('name', 'id'),
        ]);
    }
}
