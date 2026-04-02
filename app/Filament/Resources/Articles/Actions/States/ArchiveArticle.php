<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Actions\States;

use App\Builders\ArticleBuilder;
use Illuminate\Support\HtmlString;
use App\Enums\Articles\ArchiveReason;
use App\Enums\LanguageStatus;
use App\Models\Article;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\{Select, Textarea};
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\{Get, Set};
use Filament\Support\Enums\Width;

/**
 * ArchiveAction provides the interface for archiving dictionary articles.
 *
 * This action handles the process of moving articles to an archived state, where they remain in the system but are hidden from end users.
 * The action includes confirmation dialogs and permission checks to ensure proper usage.
 *
 * @property Article $record The dictionary arcticle being archived
 *
 * @package App\Filament\Resources\ArticleResource\Actions\State
 */
final class ArchiveArticle extends Action
{
    use CanCustomizeProcess;

    /**
     * Defines the visual icon for the archive action.
     * Uses the archive box icon from Heroicons to maintain consistency with the application's visual language.
     */
    protected string $actionIcon = 'heroicon-o-archive-box';

    /**
     * Provides the localized name for the archive action.
     * The translation key is processed through the Laravel translation system to support multiple languages while maintaining Dutch as the primary interface language
     */
    public static function getDefaultName(): string
    {
        return 'archive-article';
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

        $this->authorize('archive-article');
        $this->hidden(fn (Article $article): bool => $article->trashed());

        $this->label(label: __('filament/actions/archiveArticle.label'));

        // Confirmation box configuration
        $this->requiresConfirmation();
        $this->modalWidth(Width::ExtraLarge);
        $this->modalIcon($this->actionIcon);
        $this->modalHeading(heading: __('filament/actions/archiveArticle.modal.heading'));
        $this->modalDescription(description: __('filament/actions/archiveArticle.modal.description'));

        // Set up notifications for success and failures
        $this->successNotificationTitle('Het artikel is gearchiveerd');
        $this->failureNotificationTitle('Helaas! Er is iets misgelopen');

        $this->schema([
            Grid::make(12)
                ->schema([
                    Select::make('reason')
                        ->columnSpan(6)
                        ->label('Reden tot archivering')
                        ->options(ArchiveReason::class)
                        ->native(false)
                        ->afterStateUpdated(fn (Set $set, ?ArchiveReason $state) => $set('archiving_reason', $state->getDescription()))
                        ->live(),

                    Select::make('redirect_article_id')
                        ->columnSpan(6)
                        ->label('Artikel verwijzing')
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search, $record) {
                            return Article::query()
                                ->where('word', 'like', "%{$search}%")
                                ->published()
                                ->when($record, fn (ArticleBuilder $query) => $query->where('id', '!=', $record->id))
                                ->limit(6)
                                ->pluck('word', 'id');
                        })
                        ->getOptionLabelUsing(fn ($value): ?string => Article::find($value)?->word),

                    Textarea::make('archiving_reason')
                        ->rows(4)
                        ->columnSpanFull()
                        ->required()
                        ->label(label: __('filament/actions/archiveArticle.form.archiving-reason.label'))
                        ->placeholder(placeholder: __('filament/actions/archiveArticle.form.archiving-reason.placeholder'))
                        ->maxLength(350)
                        ->helperText(new HtmlString('Deze tekst zal <strong>zichtbaar</strong> zijn voor eindgebruiker.'))
                        ->default(null),
            ])
        ]);

        $this->action(function (array $data, Article $article): void {
            // Attempt to transition the article to the "archived" state withing a process that can be customized.
            if ($this->process(fn (Article $article): bool => $article->articleStatus()->transitionToArchived($data['archiving_reason'], $data['redirect_article_id']))) {
                $this->success();
                return;
            }

            // If the transition fails, display a failure message.
            $this->failure();
        });
    }
}
