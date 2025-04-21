<?php

declare(strict_types=1);

namespace App\View\Components\Articles;

use Closure;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class OverviewToolbar extends Component
{
    public function render(): ?Renderable
    {
        if (auth()->check()) {
            return view('components.articles.overview-toolbar',[
                'suggestionCount' => auth()->user()->suggestions()->count(),
            ]);
        }

        return null;
    }
}
