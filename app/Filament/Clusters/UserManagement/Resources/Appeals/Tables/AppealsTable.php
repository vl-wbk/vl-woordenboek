<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Appeals\Tables;

use App\Actions\Reputation\AppealReviewAction;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Actions\ApproveAppealAction;
use App\Filament\Clusters\UserManagement\Resources\Appeals\Actions\RejectAppealAction;
use App\Models\Appeal;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final readonly class AppealsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->heading(heading: 'Overzicht van beroepen')
            ->emptyStateIcon(icon: Heroicon::OutlinedScale)
            ->emptyStateHeading('Geen beroepen')
            ->emptyStateDescription('Er zijn geen beroepen die aan de filters voldoen.')
            ->description(description: 'Een overzicht van alle beroepsprodedures die zijn ingevuld door gebruikers.')
            ->columns(components: self::getTableColumnComponents())
            ->filters(filters: self::getTableFilters())
            ->recordActions(actions: self::getTableRecordActions())
            ->toolbarActions(actions: self::getTableBulkActions());
    }

    private static function getTableRecordActions(): array
    {
        return [
            ApproveAppealAction::make(),
            RejectAppealAction::make(),

            ViewAction::make()
                ->label('Bekijken')
        ];
    }


    private static function getTableBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                BulkAction::make('bulk_reject')
                    ->label('afwijzen')
                    ->icon(Heroicon::OutlinedXCircle)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('moderator_note')
                            ->label('Reden (optioneel)')
                            ->rows(3),
                    ])
                    ->action(function (Collection $records, array $data): void {
                        $records->each(function (Appeal $record) use ($data): void {
                            $record->status === 'pending'
                                ? app(AppealReviewAction::class)->execute($record, 'rejected', $data['moderator_note'] ?? null)
                                : null;
                        });

                        Notification::make()
                            ->title('Beroepen afgewezen')
                            ->danger()
                            ->send();
                    }),
            ]),
        ];
    }

    private static function getTableFilters(): array
    {
        return [
            SelectFilter::make('status')
                ->native(false)
                ->label('status')
                ->options([
                    'pending'  => 'In behandeling',
                    'approved' => 'Toegekend',
                    'rejected' => 'Afgewezen',
                ]),

            Filter::make('unreviewed')
                ->label('Alleen openstaand')
                ->query(fn (Builder $query): Builder => $query->where('status', 'pending'))
                ->default(),

            Filter::make('crrated_at')
                ->schema([
                    DatePicker::make('from')->native(false)->label('Van'),
                    DatePicker::make('until')->native(false)->label('Tot'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when($data['from'], fn (Builder $query): Builder => $query->whereDate('created_at', '>=', $data['from']))
                        ->when($data['until'], fn (Builder $query): Builder => $query->whereDate('created_at', '<=', $data['until']));

                }),
        ];
    }

    private static function getTableColumnComponents(): array
    {
        return [
            TextColumn::make('user.name')
                ->label('Gebruiker')
                ->weight(FontWeight::SemiBold)
                ->icon(Heroicon::OutlinedUserCircle)
                ->color('primary')
                ->iconColor('primary')
                ->searchable()
                ->sortable(),

            TextColumn::make('moderator.name')
                    ->label('Moderator')
                    ->placeholder('—')
                    ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('status')
                ->label('Status')
                ->badge()
                ->color(fn (string $state) => match ($state) {
                    'pending'  => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default    => 'gray',
                })
                ->formatStateUsing(fn (string $state) => match ($state) {
                    'pending'  => 'In behandeling',
                    'approved' => 'Toegekend',
                    'rejected' => 'Afgewezen',
                    default    => $state,
                })
                ->sortable(),

            TextColumn::make('reputationLog.points')
                ->label('Punten')
                ->badge()
                ->color('danger'),


            TextColumn::make('reputationLog.reason')
                ->label('Aanpassing')
                ->limit(45)
                ->searchable(),

            TextColumn::make('reason')
                ->label('Betwisting')
                ->toggleable(isToggledHiddenByDefault: true)
                ->limit(45),


            TextColumn::make('created_at')
                ->label('Ingediend')
                ->since()
                ->sortable(),

            TextColumn::make('reviewed_at')
                ->label('Beoordeeld')
                ->since()
                ->placeholder('—')
                ->sortable()
                ->toggleable(),
        ];
    }
}
