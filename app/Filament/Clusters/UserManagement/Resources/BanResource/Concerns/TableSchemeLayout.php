<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Concerns;

use Cog\Laravel\Ban\Models\Ban;
use Filament\Tables\Columns;

/**
 * Defines the table structure for displaying ban records in the administrative interface.
 *
 * This trait ensures the column definitions for our ban management table.
 * Each column is carefully configured to display relevant information about account deactivations in a clear and organized manner.
 * The interface text is in Dutch to maintain consistency with our application's language preferences.
 *
 * The table presents essential information including:
 * The affected user's name, the person who initiated the ban, the reason for the deactivation, expiration date, and various timestamps.
 * Many columns are toggleable to allow administrators to customize their view based on their current needs.
 */
trait TableSchemeLayout
{
    /**
     * Generates the column configuration for the bans table.
     *
     * This method constructs a comprehensive table layout that balances information density with usability.
     * Each column is configured with appropriate labels, icons, and display options.
     * Some columns are hidden by default but can be toggled on when needed for more detailed analysis.
     *
     * The table includes sophisticated features like:
     *
     * - User identification with distinct icons
     * - Searchable text fields for quick filtering
     * - Formatted date/time displays
     * - Smart empty state handling
     * - Dynamic moderator name resolution
     *
     * @return array<int, Columns\TextColumn> Array of configured table columns
     */
    public static function getTableColumnLayout(): array
    {
        return [
            Columns\TextColumn::make('id')
                ->toggleable(isToggledHiddenByDefault: true),
            Columns\TextColumn::make('bannable.name')
                ->label('Gebruiker')
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary')
                ->searchable(),
            Columns\TextColumn::make('created_by_id')
                ->label('Gedeactiveerd door')
                ->iconColor('primary')
                ->icon('heroicon-o-user-circle')
                ->toggleable(isToggledHiddenByDefault: true)
                ->searchable()
                ->formatStateUsing(fn (Ban $record): string => $record->createdBy->name ?? '-'),
            Columns\TextColumn::make('comment')
                ->label('Reden')
                ->searchable()
                ->placeholder('- niet opgegeven')
                ->toggleable(),
            Columns\TextColumn::make('expired_at')
                ->label('Verloopt op')
                ->dateTime()
                ->toggleable(),
            Columns\TextColumn::make('created_at')
                ->dateTime()
                ->label('Geregistreerd op')
                ->label('Banned at')
                ->toggleable()
                ->toggleable(isToggledHiddenByDefault: true),
            Columns\TextColumn::make('updated_at')
                ->label('Laatst gewijzigd')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
