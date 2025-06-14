<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class LabelController
{
    #[Get(uri: 'label', name: 'label:show')]
    public function __invoke(): Renderable
    {
        return view('definitions.labels.show');
    }
}
