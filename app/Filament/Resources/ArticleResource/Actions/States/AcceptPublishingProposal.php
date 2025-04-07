<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use Filament\Actions\Action;

/**
 * AcceptPublishingProposal handles the final approval of articles for publication.
 *
 * This action manages the transition of articles from the review state to published
 * status. It implements authorization checks to ensure only authorized editors can
 * approve publications. The action uses visual cues through color and iconography
 * to clearly indicate its purpose as a positive, confirmatory action.
 *
 * @property \App\Models\Article $record The article being approved for publication
 */
final class AcceptPublishingProposal extends Action
{
    /**
     * Configures the action's behavior and visual presentation.
     *
     * This setup method establishes the action's appearance and handling.
     * It uses a success color scheme and checkmark icon to indicate positive completion.
     * The method implements authorization checks through the publish-article permission and handles the state transition upon confirmation.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('success');
        $this->icon('heroicon-o-check');
        $this->authorize('publish-article', $this->record);

        $this->action(function (): void {
            $this->record->articleStatus()->transitionToReleased();
            $this->success();
        });
    }

    /**
     * Provides the default name for the action in Dutch, maintaining consistency with the application's primary language interface.
     * This text appears in approval buttons throughout the editorial interface.
     */
    public static function getDefaultName(): string
    {
        return trans('artikel publiceren');
    }
}
