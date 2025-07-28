<?php

declare(strict_types=1);

namespace App\View\Components\Articles;

use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use App\UserTypes;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\View\Component;

/**
 * The ArticleInformationToolbar component is a Blade view component responsible
 * for rendering a toolbar that displays information and actions related to an
 * article. This toolbar is conditionally rendered based on user authentication
 * and their assigned user type. It provides a quick link to edit the article
 * within the Filament admin panel if the user has appropriate permissions.
 *
 * This component acts as a bridge between the article data and its presentation
 * in a user interface, particularly for authenticated users who might need
 * administrative or editing capabilities directly on the article's view page.
 *
 * @see Article             - The Article model that this component displays information for.
 * @see ArticleResource     - The Filament resource used to generate edit links.
 * @see UserTypes           - The enum defining different user roles, used for permission checks.
 *
 * @package App\View\Components\Articles
 */
final class ArticleInformationToolbar extends Component
{
    /**
     * Create a new component instance.
     *
     * The constructor initializes the component with the specific `Article` model instance that the toolbar will display information about.
     * This article object is made publicly available as a read-only property, ensuring that it can be accessed within the component's view for rendering dynamic data.
     *
     * @param Article $word  The Article model instance for which the toolbar is being rendered. Although named `$word`, it represents an `Article` object.
     */
    public function __construct(
        public readonly Article $word,
    ) {}

    /**
     * Get the view / contents that represent the component.
     *
     * This method determines whether the article information toolbar should be rendered.
     * It first checks if a user is currently authenticated.
     * If no user is logged in, the component returns `null`, meaning the toolbar will not be displayed.
     * If a user is authenticated, the method prepares data for the `components.articles.article-information-toolbar` Blade view.
     * This data includes the `Article` model itself, a dynamically generated `editLink` pointing to the Filament admin panel's edit page for the specific article, and a boolean
     * `isNormalUser` indicating whether the authenticated user has the `Normal` user type.
     *
     * This conditional rendering and data provision ensure that the toolbar is only visible and functional for relevant users with appropriate access levels.
     *
     * @return Renderable|null  A renderable view instance if an authenticated user is found, otherwise `null`.
     */
    public function render(): ?Renderable
    {
        if (auth()->check()) {
            return view('components.articles.article-information-toolbar', [
                'word' => $this->word,
                'editLink' => ArticleResource::getUrl('edit', ['record' => $this->word]),
                'isNormalUser' => auth()->user()->user_type->is(UserTypes::Normal),
            ]);
        }

        return null;
    }
}
