<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions\States;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Icons\Heroicon;

/**
 * AcceptPublishingProposal handles the final approval of articles for publication.
 *
 * This action manages the transition of articles from the review state to published
 * status. It implements authorization checks to ensure only authorized editors can
 * approve publications. The action uses visual cues through color and iconography
 * to clearly indicate its purpose as a positive, confirmatory action.
 *
 * @property Article $record The article being approved for publication
 *
 * @package
 */
final class AcceptPublishingProposal extends Action
{
    /**
     * Provides the default name for the action in Dutch, maintaining consistency with the application's primary language interface.
     * This text appears in approval buttons throughout the editorial interface.
     */
    public static function getDefaultName(): string
    {
        return 'approve-dictionary-article';
    }

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

        $this->authorize('publish');
        $this->label('Goedkeuren');
        $this->hidden(fn (Article $article): bool => $article->trashed());
        $this->requiresConfirmation();

        $this->modalHeading('Artikel publiceren');
        $this->modalDescription('U staat op het punt om een artikel te publiceren. Met de input hieronder kun je aangeven wanneer je het artikel gepubliceerd wilt zien. Weet je zeker dat je dit wilt doen?');
        $this->modalIcon(Heroicon::OutlinedCheckBadge);

        $this->schema([
            DatePicker::make('publication_date')
                ->label('Publicatie datum')
                ->native(false)
                ->default(now())
                ->closeOnDateSelection()
        ]);

        $this->action(function (Article $article, array $data): void {
            $publicationDate = now()->parse($data['publication_date']);

            $article->articleStatus()->transitionToReleased($publicationDate);
            $this->success();
        });
    }
}
