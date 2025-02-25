<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\LabelResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Manages the relationship between Labels and Articles in the Vlaams Woordenboek.
 *
 * This relation manager provides a dedicated interface for viewing and managing articles associated with specific labels.
 * It implements a table-based view with comprehensive search, sort, and bulk management capabilities.
 *
 * @package App\Filament\Clusters\Articles\Resources\LabelResource\RelationManagers
 */
final class ArticlesRelationManager extends RelationManager
{
    /**
     * Defines the relationship name in the database schema.
     * This corresponds to the articles relationship method in the Label model.
     *
     * @var string
     */
    protected static string $relationship = 'articles';

    /**
     * Controls whether the relationship can be modified through the interface.
     * Currently set to allow full read-write access for label-article associations.
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Configures the table interface for managing article relationships.
     *
     * Creates a comprehensive view of all articles associated with a label, featuring clear headings, descriptive empty states, and intuitive column layouts.
     * The interface provides contextual information about each article while maintaining efficient space usage.
     *
     * @param  Table $table  The Filament table instance.
     * @return Table         The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Artikelen')
            ->description('Alle artikelen vanuit het woordenboek dat gekoppeld zijn aan het gereleateerde label')
            ->emptyStateIcon('heroicon-o-book-open')
            ->emptyStateHeading('Geen artikelen gevonden')
            ->emptyStateDescription('Momenteel zijn er geen artikelen gevonden die gelabeld zijn met die label. Kom later nog eens terug.')
            ->recordTitleAttribute('word')
            ->columns($this->getTableLayout())
            ->actions($this->getTableActions())
            ->bulkActions($this->getBulkActions());
    }

    /**
     * Defines individual row actions available in the table interface.
     * Currently provides only the ability to detach articles from the label, maintaining a focused set of operations for relationship management.
     *
     * @return array
     */
    protected function getTableActions(): array
    {
        return [
            Tables\Actions\DetachAction::make()
        ];
    }

    /**
     * Configures bulk actions for managing multiple article relationships simultaneously.
     * Enables efficient batch operations for detaching multiple articles from a label at once.
     *
     * @return array
     */
    protected function getBulkActions(): array
    {
        return [
            Tables\Actions\DetachBulkAction::make(),
        ];
    }

    /**
     * Defines the table column structure for displaying article information.
     * All columns support searching for efficient data filtering.
     *
     * Creates a detailed view of each areticle with:
     * - Author information with user icon
     * - Article title with emphasized styling
     * - Description preview
     * - Relationsup creation timestamp
     *
     * @return array  The configured table layout.
     */
    protected function getTableLayout(): array
    {
        return [
            Tables\Columns\TextColumn::make('author.name')
                ->label('Ingevoegd door')
                ->searchable()
                ->icon('heroicon-o-user-circle')
                ->iconColor('primary'),
            TextColumn::make('word')
                ->searchable()
                ->weight(FontWeight::SemiBold)
                ->color('primary')
                ->label('Titel'),
            TextColumn::make('description')
                ->label('Beschrijving')
                ->searchable()
                ->sortable(),
            Tables\Columns\TextColumn::make('pivot.created_at')
                ->label('Gekoppeld op')
                ->date()
                ->searchable(),
        ];
    }
}
