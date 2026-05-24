<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Tables;

use App\Models\WordOfTheDay;
use Carbon\Carbon;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final readonly class WordOfTheDaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->striped(false)
            ->heading(heading: 'Woord van de Dag Planner')
            ->description(description: 'Beheer hier de dagelijkse woorden. Elk woord wordt gekoppeld aan een specifieke datum en een bijzondere gebeurtenis of context.')
            ->groups(groups: self::registerGroups())
            ->collapsedGroupsByDefault()
            ->extremePaginationLinks()
            ->defaultGroup(group: 'scheduled_for')
            ->emptyStateIcon(icon: Heroicon::Calendar)
            ->emptyStateHeading(heading: 'Geen woorden ingepland')
            ->emptyStateDescription(description: 'Het lijkt erop dat er met de matchende criteria geen ingeplande woorden zijn gevonden.')
            ->emptyStateActions(actions: self::registerEmptyStateActions())
            ->columns(components: self::registerTableColumnLayout())
            ->filters(filters: self::registerTableFilters())
            ->recordActions(actions: self::registerRecordActions())
            ->toolbarActions(actions: self::registerToolbarActions())
            ->defaultSort('scheduled_for', 'desc');;
    }

    /**
     * @return array<int, Group>
     */
    private static function registerGroups(): array
    {
        return [
            Group::make('scheduled_for')
                ->label('Maand')
                ->titlePrefixedWithLabel(false)
                ->getTitleFromRecordUsing(fn ($record): string => $record->scheduled_for->format('F Y'))
                ->date()
                ->collapsible()
        ];
    }

    /**
     * @return array<int, CreateAction>
     */
    private static function registerEmptyStateActions(): array
    {
        $isVisible = WordOfTheDay::count() === 0;

        return [
            CreateAction::make()
                ->label('Woord inplannen')
                ->visible($isVisible)
                ->icon(Heroicon::OutlinedPlusCircle)
        ];
    }

    /**
     * @return array<int, Filter|TernaryFilter>
     */
    private static function registerTableFilters(): array
    {
        return [
            Filter::make('scheduled_for')
                ->schema([
                    DatePicker::make('van')->native(false)->displayFormat('d-m-Y')->closeOnDateSelection()->placeholder('dd/mm/yyyy'),
                    DatePicker::make('tot')->native(false)->displayFormat('d-m-Y')->closeOnDateSelection()->placeholder('dd/mm/yyyy'),
                ])
                ->query(fn (Builder $query, array $data) => $query
                    ->when($data['van'], fn ($q, $date) => $q->whereDate('scheduled_for', '>=', $date))
                    ->when($data['tot'], fn ($q, $date) => $q->whereDate('scheduled_for', '<=', $date))
                )
                ->indicateUsing(fn (array $data) => self::formatDateRangeIndicator($data)),

            TernaryFilter::make('toekomst')
                ->label('Planning status')
                ->native(false)
                ->placeholder('Alle woorden')
                ->trueLabel('Toekomstige woorden')
                ->falseLabel('Verleden woorden')
                ->default(true)
                ->queries(
                    true: fn (Builder $query) => $query->whereDate('scheduled_for', '>=', now()),
                    false: fn (Builder $query) => $query->whereDate('scheduled_for', '<', now()),
                )
        ];
    }

    /**
     * @param  array<string> $data
     * @return string|null
     */
    private static function formatDateRangeIndicator(array $data): ?string
    {
        if (! $data['van'] && ! $data['tot']) {
            return null;
        }

        $from = $data['van'] ? Carbon::parse($data['van'])->format('d-m-Y') : '...';
        $to = $data['tot'] ? Carbon::parse($data['tot'])->format('d-m-Y') : '...';

        return "Periode: {$from} tot {$to}";
    }

    /**
     * @return array<int, TextColumn>
     */
    private static function registerTableColumnLayout(): array
    {
        return [
            TextColumn::make('scheduled_for')
                ->label('Datum')
                ->date()
                ->sortable()
                ->searchable()
                ->weight(fn (WordOfTheDay $record) => $record->scheduled_for->isToday() ? FontWeight::Bold : FontWeight::Medium)
                ->color(fn (WordOfTheDay $record) => $record->scheduled_for->isToday() ? 'success' : null),

            TextColumn::make('planner.name')
                ->label('Planner')
                ->icon(Heroicon::OutlinedUserCircle)
                ->toggleable()
                ->toggledHiddenByDefault(),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->state(fn (WordOfTheDay $record): string => $record->scheduled_for->isPast() && !$record->scheduled_for->isToday() ? 'Verstreken' : 'Gepland')
                ->color(fn (string $state): string => $state === 'Gepland' ? 'success' : 'gray')
                ->icon(fn (string $state): Heroicon => $state === 'Gepland' ? Heroicon::Calendar : Heroicon::CheckCircle),

            TextColumn::make('article.word')
                ->label('Artikel / Woord')
                ->color('primary')
                ->copyable()
                ->weight(FontWeight::Bold)
                ->searchable()
                ->copyable()
                ->copyMessage('Woord gekopieerd naar klembord'),

            TextColumn::make('scheduling_reason')
                ->label('Aanleiding')
                ->placeholder('- geen aanleiding of gebeurtenis geregistreerd')
                ->limit(50)
                ->tooltip(fn (WordOfTheDay $record): string => (string) $record->scheduling_reason)
                ->searchable(),
        ];
    }

    /**
     * @return array<int, ViewAction|ActionGroup>
     */
    private static function registerRecordActions(): array
    {
        return [
            ViewAction::make()
                ->modalFooterActions([EditAction::make(), DeleteAction::make()])
                ->modalIcon(Heroicon::OutlinedInformationCircle)
                ->modalIconColor('primary')
                ->modalHeading(fn ($record) => "Woord van de Dag: " . $record->scheduled_for->format('d-m-Y'))
                ->modalDescription('Hieronder vind je de details van de planning voor dit specifieke artikel.'),

            ActionGroup::make([
                EditAction::make(),
                DeleteAction::make()
            ])->tooltip('Opties'),
        ];
    }

    /**
     * @return array<int, BulkActionGroup>
     */
    private static function registerToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->failureNotificationTitle("Verwijderen van WOTD's mislukt")
                    ->authorizeIndividualRecords('delete'),
            ]),
        ];
    }
}
