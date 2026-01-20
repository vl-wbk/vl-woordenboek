<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Tables;

use App\Enums\Volunteers\ApplicationState;
use App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Actions\ViewAction;
use Deldius\UserField\UserColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final readonly class VolunteerApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Aanmeldingen')
            ->description('In de onderstaande tabel vind je een overzicht van alle aanmeldingen als vrijwilliger die uitgevoerd zijn door bestaande gebruikers van het Vlaams Woordenboek')
            ->emptyStateIcon(Heroicon::OutlinedDocumentMinus)
            ->emptyStateHeading('Geen aanmeldingen gevonden')
            ->emptyStateDescription('Het lijkt er op dat er geen aanmeldingen zijn gevonden matchende de criteria die je hebt opgegeven')
            ->columns(components: self::registerTableColumns())
            ->filters(filters: self::configureTableFilters())
            ->recordActions(actions: self::configureRecordActions())
            ->toolbarActions(actions: self::configureToolbarActions());
    }

    private static function configureRecordActions(): array 
    {
        return [
            ViewAction::make(),
        ];
    }

    private static function configureTableFilters(): array 
    {
        return [
            SelectFilter::make('state')
                ->label('Status')
                ->native(false)
                ->options(ApplicationState::class)
        ];
    }   

    private static function registerTableColumns(): array 
    {
        return [
            TextColumn::make('id')
                ->color('primary')
                ->weight(FontWeight::Bold)
                ->label('#')
                ->sortable(),
            
                TextColumn::make('state')
                ->label('Status')
                ->sortable()
                ->badge(),

            UserColumn::make('user.id')
                ->label('Gebruiker')
                ->searchable(),
            
            TextColumn::make('volunteerPosition.name')
                ->label('Gewenste positie'),
            
            TextColumn::make('created_at')
                ->label('Aangemeld op')
                ->date()
                ->sinceTooltip()
                ->sortable()
        ];
    }

    private static function configureToolbarActions(): array 
    {
        return [];
    }
}
