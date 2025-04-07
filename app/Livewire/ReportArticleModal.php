<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

/**
 * ReportArticleModal is a Livewire component that displays a modal for reporting an article.
 *
 * This component takes in an Article model and makes it available to a corresponding view,
 * where users can submit a report regarding issues with the article.
 *
 * Key points for future developers:
 * - The component expects an Article instance to be passed during initialization (via route model binding or otherwise).
 * - The mount() method ensures that the provided article model is set up correctly for use within the component.
 * - The render() method returns a view ("livewire.report-article-modal") and passes the Article instance to it,
 *   so that the view can display details about the article and provide an interface for reporting.
 *
 * Developers can easily extend or customize this component to include extra validation, dynamic behavior, or additional data as required by the project's reporting workflow.
 */
final class ReportArticleModal extends Component
{
    /**
     * The article to be reported.
     */
    public Article $article;

    /**
     * Initializes the component with the given article.
     * This method sets up the ReportArticleModal by storing the Article instance so that it can be used in the view.
     *
     * @param  Article  $article  The article being reported.
     */
    public function mount(Article $article): void
    {
        $this->article = $article;
    }

    /**
     * Renders the reporting modal view.
     *
     * This method returns the view 'livewire.report-article-modal' and passes the Article instance
     * to the view. The view can then display relevant details about the article and provide an interface
     * for the user to submit a report.
     *
     * @return Renderable The view that displays the report modal.
     */
    public function render(): Renderable
    {
        return view('livewire.report-article-modal', data: [
            'article' => $this->article,
        ]);
    }
}
