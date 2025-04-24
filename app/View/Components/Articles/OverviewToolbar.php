<?php

declare(strict_types=1);

namespace App\View\Components\Articles;

use Illuminate\Contracts\Support\Renderable;
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

        // Dunno why this isn't covered in tests. mainly because we have also tests in situations where no user is authenticated
        // @codeCoverageIgnoreStart
        return null;
        // @codeCoverageIgnoreEnd
    }
}
