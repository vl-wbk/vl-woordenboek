<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Etymology;

use App\Models\Etymology;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ReportController
{
    #[Get(uri: 'etymology/melding/{etymology}', name: 'etymology:report')]
    public function create(Etymology $etymology): Renderable
    {
    }
}

