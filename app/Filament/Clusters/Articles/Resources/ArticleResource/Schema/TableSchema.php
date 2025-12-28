<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\Schema;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Actions\BulkArchiveAction;
use App\Filament\Exports\ArticleExporter;
use App\Models\Article;
use Filament\Actions\{ActionGroup,
    BulkActionGroup,
    DeleteAction,
    DeleteBulkAction,
    EditAction,
    ExportBulkAction,
    RestoreAction,
    RestoreBulkAction,
    ViewAction};
use Deldius\UserField\UserColumn;
use Filament\Support\Enums\{FontWeight, Width};
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\{Filter, SelectFilter, TrashedFilter};
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final readonly class TableSchema
{
    public static function configure(Table $table): Table
    {
        return $table
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
                    RestoreAction::make()->color('danger'),
                    DeleteAction::make()->authorizationNotification(),
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
                ->emptyStateDescription(fn (Article $article): ?string => $article->contributor_name)
                ->label('Ingevoegd door'),

            TextColumn::make('word')
                ->searchable()
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->label(label: __('Lemma')),

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
     * @return array<int, BulkActionGroup>
     */
    private static function getToolbarActions(): array
    {

        return [
            BulkActionGroup::make([
                ExportBulkAction::make()->exporter(ArticleExporter::class)
                    ->modalWidth(Width::Large)
                    ->modalDescription(description: __('Gegevens nodig in een ander programma? Geen probleem! Selecteer de kolommen die je nodig hebt en je kunt vervolgens de gegevens downloaden in een .xlsx of .csv bestanden downloaden'))
                    ->icon(Heroicon::OutlinedArrowDownTray)
                    ->slideOver(),

                BulkArchiveAction::make(),

                BulkActionGroup::make([
                    RestoreBulkAction::make(),
                    DeleteBulkAction::make(),
                ])->dropdown(false),
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
                ->label('status')
                ->multiple()
                ->options(ArticleStates::class),

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
