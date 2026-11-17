<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\FeatureBans\Tables;

use App\Models\FeatureBan;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

final readonly class FeatureBansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->emptyStateIcon(icon: Heroicon::OutlinedNoSymbol)
            ->emptyStateHeading('Geen beperkingen geregistreerd')
            ->emptyStateDescription('Momenteel zijn er nog geen account beperkingen gevonden die matchen met de opgegeven criteria.')
            ->defaultSort('created_at', 'desc')
            ->columns(components: self::configureTableColumns())
            ->filters(filters: self::configureTableFilters(), layout: FiltersLayout::Modal)
            ->recordActions(actions: self::configureTableActions())
            ->toolbarActions(actions: self::registerToolbarActions());
    }

    private static function registerToolbarActions(): array 
    {
        return [
            DeleteBulkAction::make()
                ->label('Beperkingen opheffen'),
        ];
    }

    private static function configureTableActions(): array 
    {
        return [
            ViewAction::make()
                ->modalCloseButton(false)
                ->modalIcon(Heroicon::OutlinedShieldExclamation)
                ->modalIconColor('danger')
                ->modalHeading('Functionaliteits beperking informatie')
                ->modalDescription('Alle beschikbare informatie van een functionaliteits beperking van een gebruiker.'),

            DeleteAction::make()
                ->modalSubmitActionLabel('Beperking opheffen')
                ->modalHeading('Beperking opheffen')
                ->label('Beperking opheffen')
                ->requiresConfirmation()
                ->successNotificationTitle('De account beperking is opgeheven'),
        ];
    }

    private static function configureTableColumns(): array 
    {
        return [
            TextColumn::make('user.name')
                ->label('Gebruiker')
                ->searchable()
                ->sortable(),

            IconColumn::make('active')
                ->boolean()
                ->state(fn (FeatureBan $record) => $record->isActive()),

            TextColumn::make('feature')
                ->label('Functionaliteit')
                ->badge()
                ->icon(Heroicon::OutlinedNoSymbol)
                ->color('danger')
                ->formatStateUsing(fn ($state) => config("features.bannable.$state", $state)),

            TextColumn::make('bannedBy.name')
                ->label('Beperkt door')
                ->toggleable(), 

            TextColumn::make("expires_at")
                ->dateTime()
                ->placeholder('Permanent')
                ->sortable(),
        ];
    }

    private static function configureTableFilters(): array 
    {
        return [
            SelectFilter::make('feature')
                ->label('Functionaliteit')
                ->options(config('flemish-dictionary.features.bannable'))
                ->native(false), 

            TernaryFilter::make('active')
                ->label('Actieve beperkingen')
                ->native(false)
                ->queries(
                    true: fn (Builder $builder): Builder => $builder->active(),
                    false: fn (Builder $builder): Builder => $builder->expired(),
                    blank: fn (Builder $builder): Builder => $builder
                ),
        ];
    }
}
