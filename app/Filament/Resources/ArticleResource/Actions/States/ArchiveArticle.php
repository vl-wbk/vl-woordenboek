<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Actions\States;

use App\Models\Article;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;

/**
 * ArchiveAction provides the interface for archiving dictionary articles.
 *
 * This action handles the process of moving articles to an archived state, where they remain in the system but are hidden from end users.
 * The action includes confirmation dialogs and permission checks to ensure proper usage.
 *
 * @property \App\Models\Article $record The dictionary arcticle being archived
 *
 * @package App\Filament\Resources\ArticleResource\Actions\State
 */
final class ArchiveArticle extends Action
{
    /**
     * Defines the visual icon for the archive action.
     * Uses the archive box icon from Heroicons to maintain consistency with the application's visual language.
     */
    protected string $actionIcon = 'heroicon-o-archive-box';

    /**
     * Provides the localized name for the archive action.
     * The translation key is processed through laravel's translation system to support multiple languages whule maintaining Dutch as the primary nterface language
     */
    public static function getDefaultName(): string
    {
        return trans('Artikel archiveren');
    }

    /**
     * Configures the action's behavior and appearance.
     *
     * This setup method:
     * - Sets the visual styling (gray color scheme and archive icon)
     * - Implements permission checks through the authorization system
     * - Configures the confirmation dialog with appropriate warnings
     * - Handles the state transition when the action is confirmed
     *
     * The confirmation dialog ensures users understand the implications of archiving an article, particularly regarding its reduced visibility.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->color('gray');
        $this->icon($this->actionIcon);
        $this->authorize('archive-article', $this->record);

        // Confirmation box configuration
        $this->requiresConfirmation();
        $this->modalIcon($this->actionIcon);
        $this->modalHeading('Artikel archiveren');
        $this->modalDescription('Indien u het artikel in het archief stopt. Zal deze echter een beperkte zichtbaarheid hebben. En niet raadplaagbaar zijn voor eind gebruikers');
        $this->form([
            Textarea::make('archiving_reason')
                ->rows(4)
                ->label('Archiverings redenen')
                ->placeholder('Beschrijf kort waarom het artikel gearchiveerd word')
                ->maxLength(350)
        ]);

        $this->action(function (array $data, Article $article): void {
            $article->articleStatus()->transitionToArchived($data['archiving_reason']);
            $this->success();
        });
    }
}
