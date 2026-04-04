<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Concepts;

use App\Models\Concept;
use App\Models\PartOfSpeech;
use App\Models\Region;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class EditController
{
    use AuthorizesRequests;

    #[Get(uri: 'concept/{concept}', name: 'concepts:edit', middleware: ['auth', 'verified'])]
    public function edit(Request $request, Concept $concept): Renderable
    {
        $this->authorize('update',$concept);

        return view('concepts.edit', data: [
            'user' => $request->user(),
            'concept' => $concept,
            'regions' => Region::query()->pluck('name', 'id'),
            'partOfSpeeches' => PartOfSpeech::query()->where('suggestible', true)->pluck('name', 'id'),
        ]);
    }
}
