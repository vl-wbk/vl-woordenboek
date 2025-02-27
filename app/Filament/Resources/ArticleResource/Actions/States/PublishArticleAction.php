<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;

/**
 * PublishArticleAction handles the submission for publication review.
 *
 * This action manages the transition of articles form draft state to the approval queue.
 * It provides a confirmation interface with clear messaging about the review process and ensures proper authorization before allowing submission.
 * The action maintains visual consistency through standarized icons and color schemes while supporting the Dutch-language interface requirements.
 *
 * @property \App\Models\Article $record The dictionary article being submitted for publication
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

        $this->icon('heroicon-o-paper-airplane');
        $this->color('gray');

        // Configuration on the conformation model
        $this->requiresConfirmation();
        $this->modalHeading(fn (): string => trans('Artikel insturen voor publicatie.'));
        $this->modalDescription('Nadat u het artikel instuurt voor nazicht zal hij/zij het artikel nakijken en mogelijks goedkeuren voor publictatie');
        $this->modalSubmitActionLabel('Insturen');
        $this->authorize('sendForApproval', $this->record);
        $this->modalIcon('heroicon-o-paper-airplane');

        $this->action(function (): void {
            $this->record->articleStatus()->transitionToApproved();
            $this->success();
        });
    }
}
