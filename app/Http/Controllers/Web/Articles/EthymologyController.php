<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Models\Article;
use App\Models\Etymology;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class EthymologyController
{
    #[Get(uri: 'ethymology/{etymology}', name: 'etymology:show')]
    public function show(Etymology $etymology): Renderable
    {
        return view('definitions.etymology.show', data: [
            'etymology' => $etymology,
            'etymologies' => $etymology->article->etymology()->get()
        ]);
    }
}
