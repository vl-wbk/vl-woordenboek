<?php

declare(strict_types=1);

namespace App\View\Components\Articles;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

final class OverviewToolbar extends Component
{
    public function render(): ?Renderable
    {
        return view('components.articles.overview-toolbar',[
            'suggestionCount' => auth()->user()?->suggestions()->count() ?? 0,
        ]);
    }
}
