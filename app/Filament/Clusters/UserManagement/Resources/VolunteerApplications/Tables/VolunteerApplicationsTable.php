<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Tables;

use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * @todo Document this table class
 */
final readonly class VolunteerApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading(heading: __('filament/resources/volunteer-applications.table.heading'))
            ->description(description: __('filament/resources/volunteer-applications.table.description', ['app' => config('app.name', 'Laravel')]))
            ->emptyStateIcon(icon: Heroicon::OutlinedUserPlus)
            ->emptyStateHeading()
            ->emptyStateDescription()
            ->columns(components: self::getTableColumnComponents())
            ->filters(filters: self::getFilterComponents())
            ->recordActions(actions: self::getRecordActions());
    }

    private static function getTableColumnComponents(): array 
    {
        return [
            // TODO: Build up table column components 
        ];
    }

    private static function getFilterComponents(): array 
    {
        return [
            // TODO: Filter for the application state 
            // TODO: Filter for the reviewer
        ];
    }

    private static function getRecordActions(): array 
    {
        return [
            ViewAction::make(),
        ];
    }
}
