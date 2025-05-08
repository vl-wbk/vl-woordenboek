<?php

declare(strict_types=1);

namespace App\View\Components\Articles;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

/**
 * Class OverviewToolbar
 *
 * This view component is responsible for rendering the toolbar displayed on the article overview page.
 * It provides information such as the number of bookmarks and suggestions associated with the currently authenticated user.
 *
 * @package App\View\Components\Articles
 */
final class OverviewToolbar extends Component
{
    /**
     * Render the component.
     *
     * This method retrieves the bookmark and suggestion counts for the authenticated user and passes them to the view for rendering.
     * If no user is authenticated, the counts default to 0.
     *
     * @return Renderable - The rendered view of the toolbar
     */
    public function render(): Renderable
    {
        return view('components.articles.overview-toolbar',[
            'bookmarkCount' => auth()->user()?->bookmarks()->count() ?? 0,
            'suggestionCount' => auth()->user()?->suggestions()->count() ?? 0,
        ]);
    }
}
