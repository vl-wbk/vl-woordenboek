<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Tables;

use App\Models\WordOfTheDay;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final readonly class WordOfTheDaysTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: 'Woord van de Dag Planner')
            ->description(description: 'Beheer hier de dagelijkse woorden. Elk woord wordt gekoppeld aan een specifieke datum en een bijzondere gebeurtenis of context.')
            ->emptyStateIcon(icon: Heroicon::Calendar)
            ->emptyStateHeading(heading: 'Geen woorden ingepland')
            ->emptyStateDescription(description: 'Het lijkt erop dat er met de matchende criteria geen ingeplande woorden zijn gevonden.')
            ->emptyStateActions(actions: self::registerEmptyStateActions())
            ->columns(components: self::registerTableColumnLayout())
            ->filters(filters: self::registerTableFilters())
            ->recordActions(actions: self::registerRecordActions())
            ->toolbarActions(actions: self::registerToolbarActions())
            ->defaultSort('scheduled_for', 'asc');;
    }

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

    private static function registerTableFilters(): array 
    {
        return [
            Filter::make('scheduled_for')
                ->schema([
                    DatePicker::make('van')->native(false)->placeholder('dd/mm/yyyy'),
                    DatePicker::make('tot')->native(false)->placeholder('dd/mm/yyyy'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['van'], fn ($query, $date) => $query->whereDate('scheduled_for', '>=', $date))
                        ->when($data['tot'], fn ($query, $date) => $query->whereDate('scheduled_for', '<=', $date));
                })
                ->indicateUsing(function (array $data): ?string {
                    if (! $data['van'] && ! $data['tot']) {
                        return null;
                    }

                    $label = 'Periode: ';

                    if ($data['van']) {
                        $label .= \Carbon\Carbon::parse($data['van'])->format('d-m-Y');
                    } else {
                        $label .= '...';
                    }

                    $label .= ' tot ';

                    if ($data['tot']) {
                        $label .= \Carbon\Carbon::parse($data['tot'])->format('d-m-Y');
                    } else {
                        $label .= '...';
                    }

                    return $label;
                }),
        
            TernaryFilter::make('toekomst')
                ->label('Planning status')
                ->native(false)
                ->placeholder('Alle woorden')
                ->trueLabel('Toekomstige woorden')
                ->falseLabel('Verleden woorden')
                ->queries(
                    true: fn (Builder $query) => $query->whereDate('scheduled_for', '>=', now()),
                    false: fn (Builder $query) => $query->whereDate('scheduled_for', '<', now()),
                )
        ];
    }

    private static function registerTableColumnLayout(): array 
    {
        return [
            TextColumn::make('planner.name')
                ->label('Ingepland door')
                ->icon(Heroicon::OutlinedUserCircle)
                ->iconColor('primary')
                ->toggleable()
                ->toggledHiddenByDefault()
                ->searchable(),

            TextColumn::make('article.word')
                ->label('Artikel')
                ->searchable()
                ->weight(FontWeight::Bold)
                ->color('primary'),

            TextColumn::make('scheduled_for')
                ->label('Ingepland voor')
                ->searchable()
                ->date()
                ->sortable()
                ->sinceTooltip(),

            TextColumn::make('scheduling_reason')
                ->label('Gebeurtenis / Aanleiding')
                ->searchable()
                ->limit(75),

            TextColumn::make('created_at')
                ->label('Ingepland op')
                ->searchable()
                ->date()
                ->sortable()
                ->sinceTooltip()
        ];
    }

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
            ]),
        ];
    }

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
