<?php

namespace App\Http\Controllers\Definitions;

use App\Models\Word;
use Illuminate\Contracts\Support\Renderable;

final readonly class DefinitionInformationController
{
    public function __invoke(Word $word): Renderable
    {
        return view('definitions.show', compact('word'));
    }
}
