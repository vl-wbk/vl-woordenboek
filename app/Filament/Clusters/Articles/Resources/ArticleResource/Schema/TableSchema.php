<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Schema;

use App\Enums\ArticleStates;
use App\Enums\LanguageStatus;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Actions\BulkArchiveAction;
use App\Filament\Resources\Articles\Actions\SoftDeleteArticleAction;
use App\Filament\Resources\Articles\Actions\RestoreArticleAction;
use App\Filament\Resources\Articles\ArticleResource;
use App\Models\Article;
use Filament\Actions\{Action, ActionGroup,
    BulkActionGroup,
    EditAction,
    ForceDeleteAction,
    ViewAction};
use Deldius\UserField\UserColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Support\Enums\{Alignment, FontWeight, Width};
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{Filter, SelectFilter, TrashedFilter};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;

final readonly class TableSchema
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(
                fn (Builder $query) => $query->with(['author', 'partOfSpeech'])
                    ->select(['articles.id', 'articles.author_id', 'articles.characteristics', 'articles.part_of_speech_id', 'articles.state', 'articles.word', 'articles.updated_at', 'articles.created_at'])
            )
            ->headerActions(self::configureHeaderActions())
            ->deferLoading()
            ->striped(false)
            ->heading(heading: __('Woordenboek artikelen'))
            ->description(description: __('Een overzicht van alle artikelen die geregistreerd staan In het Vlaams Woordenboek gebruik de filters om de woorden te verkrijgen per status.'))
            ->emptyStateIcon(icon: Heroicon::OutlinedLanguage)
            ->emptyStateHeading(heading: __('Geen artikelen gevonden'))
            ->emptyStateDescription(description: __("Momenteel konden we geen artikelen (lemma's) vinden met de matchende criteria. Kom later nog eens terug."))
            ->paginated(condition: [10, 25, 50, 75])
            ->columns(components: self::getTableColumns())
            ->recordActions(actions: self::getRecordActions())
            ->filters(filters: self::getFilters())
            ->filtersLayout(FiltersLayout::Modal)
            ->toolbarActions(actions: self::getToolbarActions())
            ->selectCurrentPageOnly()
            ->defaultSort('created_at', 'desc');
    }

    /**
     * @return array<int, ViewAction|ActionGroup>
     */
    private static function getRecordActions(): array
    {
        return [
            ViewAction::make(),

            ActionGroup::make([
                EditAction::make()->authorizationNotification(),
                ActionGroup::make([
                    RestoreArticleAction::make()->color('danger'),
                    SoftDeleteArticleAction::make()->authorizationNotification(),
                    ForceDeleteAction::make()->authorizationNotification(),
                ])->dropdown(false)
            ])
        ];
    }

    /**
     * @return array<int, UserColumn|TextColumn>
     */
    private static function getTableColumns(): array
    {
        return [
            UserColumn::make('author_id')
                ->description(fn (Article $article): string => "{$article->author->firstname} {$article->author->lastname}")
                ->emptyStateHeading(config('app.name', 'Laravel')) // Custom empty state heading
                ->emptyStateDescription(fn (Article $article): ?string => $article->contributor_name ?? 'Anonieme gebruiker')
                ->label('Ingevoegd door'),

            TextColumn::make('word')
                ->searchable()
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->label(label: __('Lemma'))
                ->sortable(),

            TextColumn::make('state')
                ->label('status')
                ->badge()
                ->searchable(),

            TextColumn::make('partOfSpeech.name')
                ->label(label: __('woordsoort'))
                ->placeholder(placeholder: __('-'))
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('characteristics')
                ->label(label: __('kenmerken'))
                ->placeholder(placeholder: __('-'))
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),

            TextColumn::make('created_at')
                ->label(label: __('Toegevoegd op'))
                ->sortable()
                ->date()
                ->toggleable(),

            TextColumn::make('updated_at')
                ->label(label: __('Laatst gewijzigd'))
                ->sortable()
                ->date()
                ->toggleable(),
        ];
    }

    /**
     * @return Action[]
     */
    private static function configureHeaderActions(): array
    {
        return [
            Action::make('quick-find')
                ->label('Ga naar')
                ->color('gray')
                ->icon(Heroicon::OutlinedMagnifyingGlassCircle)
                ->modalIcon(Heroicon::OutlinedMagnifyingGlassCircle)
                ->modalIconColor('primary')
                ->modalAlignment(Alignment::Center)
                ->modalFooterActionsAlignment(Alignment::Center)
                ->modalCancelAction(false)
                ->modalWidth(Width::Small)
                ->modalHeading('Ga naar artikel')
                ->schema([
                    TextInput::make('id')
                        ->label('Artikel ID')
                        ->numeric()
                        ->required()
                        ->exists()
                        ->validationMessages([
                            'numeric' => 'De referentie ID kan alleen een numerieke waarde bevatten.',
                            'exists' => 'Er is geen artikel met de opgegeven referentie ID gevonden.'
                        ])
                ])->action(function (array $data): RedirectResponse {
                    $article = Article::findOrFail($data['id']);

                    return redirect(ArticleResource::getUrl('view', ['record' => $article]));
                })
        ];
    }

    /**
     * @return array<int, BulkActionGroup>
     */
    private static function getToolbarActions(): array
    {

        return [
            BulkActionGroup::make([
                BulkArchiveAction::make(),

                // BulkActionGroup::make([
                //    RestoreBulkAction::make(),
                //    ForceDeleteBulkAction::make(),
                //    DeleteBulkAction::make(),
                // ])->dropdown(false),
            ]),
        ];
    }

    /**
     * @return array<int, SelectFilter|TrashedFilter|Filter>
     */
    private static function getFilters(): array
    {
        return [
            SelectFilter::make('state')
                ->label('Artikel status')
                ->multiple()
                ->options(ArticleStates::class),

            SelectFilter::make('status')
                ->label('Taal status')
                ->options(LanguageStatus::class)
                ->native(false),

            SelectFilter::make('disclaimer')
                ->native(false)
                ->relationship('disclaimer', 'name'),

            TrashedFilter::make()
                ->native(false)
                ->visible(fn(): bool => auth()->user()->canAny('restore', Article::class)),

            Filter::make(name: __('assigned'))
                ->label(label: __('Toegewezen aan mij'))
                ->query(fn(Builder $query): Builder => $query->where('editor_id', auth()->id())),
        ];
    }
}
