<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions\States;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;

/**
 * PublishArticleAction handles the submission for publication review.
 *
 * This action manages the transition of articles form draft state to the approval queue.
 * It provides a confirmation interface with clear messaging about the review process and ensures proper authorization before allowing submission.
 * The action maintains visual consistency through standarized icons and color schemes while supporting the Dutch-language interface requirements.
 *
 * @property Article $record The dictionary article being submitted for publication
 *
 * @package App\Filament\Resources\ArticleResource\Actions\States;
 */
final class PublishArticleAction extends Action
{
    /**
     * Provides the default name for the action in Dutch, maintaining consistency with the application's primary language interface.
     * This text appears in buttons and navigation elements throughout the system.
     */
    public static function getDefaultName(): string
    {
        return 'insturen voor publicatie';
    }

    /**
     * Configures the action's behavior and visual presentation.
     *
     * This setup method establishes the action's appearance and interaction flow.
     * It configures the confirmation dialog with appropriate messaging about the review process, sets up authorization checks, and handles the state
     * transition when confirmed. The paper airplane icon visually reinforces the submission concept while maintaining the application's design language.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->authorize('sendForApproval');

        $this->icon(Heroicon::OutlinedPaperAirplane);
        $this->color('gray');

        // Configuration on the confirmation model
        $this->requiresConfirmation();
        $this->modalHeading(fn(): string => trans('Artikel insturen voor publicatie.'));
        $this->modalDescription('Nadat je het artikel instuurt voor nazicht zal de eindredacteur het artikel nakijken en goedkeuren voor publicatie of verzoeken om bijkomende correcties aan te brengen.');
        $this->modalSubmitActionLabel('Insturen');
        $this->modalIcon(Heroicon::OutlinedPaperAirplane);

        $this->action(function (Article $article): void {
            $article->articleStatus()->transitionToApproved();
            $this->success();
        });
    }
}
