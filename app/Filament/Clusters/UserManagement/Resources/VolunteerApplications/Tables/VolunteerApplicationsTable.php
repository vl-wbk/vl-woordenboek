<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Tables;

use App\Models\VolunteerApplication;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
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
            ->emptyStateHeading(heading: __('filament/resources/volunteer-applications.empty-state.heading'))
            ->emptyStateDescription(description: __('filament/resources/volunteer-applications.empty-state.description'))
            ->columns(components: self::getTableColumnComponents())
            ->filters(filters: self::getFilterComponents())
            ->recordActions(actions: self::getRecordActions());
    }

    private static function getTableColumnComponents(): array 
    {
        return [
            TextColumn::make('id')
                ->label(label: '#')
                ->color('primary')
                ->weight(FontWeight::Bold)
                ->sortable(),

            TextColumn::make('reviewer.name')
                ->label(label: __('filament/resources/volunteer-applications.table.columns.reviewer'))
                ->searchable()
                ->placeholder('-')
                ->toggleable()
                ->toggledHiddenByDefault(),

            TextColumn::make('state')
                ->label(label: __('filament/resources/volunteer-applications.table.columns.state'))
                ->badge(),

            TextColumn::make('user.name')
                ->label(label: __('filament/resources/volunteer-applications.table.columns.user'))
                ->searchable(),

            TextColumn::make('role')
                ->label(label: __('filament/resources/volunteer-applications.table.columns.position'))
                ->iconColor('primary')
                ->icon(fn (VolunteerApplication $volunteerApplication) => $volunteerApplication->role->getIcon()),

            TextColumn::make('created_at')
                ->label(label: __('filament/resources/volunteer-applications.table.columns.created-at'))
                ->since()
                ->sortable(),

            TextColumn::make('closed_at')
                ->label(label: __('filament/resources/volunteer-applications.table.columns.closed-at'))
                ->date()
                ->placeholder('-')
                ->sinceTooltip()
                ->toggleable()
                ->toggledHiddenByDefault()
                ->sortable(),
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
