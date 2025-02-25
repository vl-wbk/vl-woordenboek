<?php

namespace App\Http\Controllers\Definitions;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;

final readonly class DefinitionInformationController
{
    public function __invoke(Article $word): Renderable
    {
        return view('definitions.show', compact('word'));
    }
}
