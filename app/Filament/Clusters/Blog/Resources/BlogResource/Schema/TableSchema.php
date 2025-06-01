<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Schema;

use Filament\Tables\Columns;

/**
 * Class TableSchema
 *
 * This class serves as a dedicated blueprint for defining the columns that will be displayed in a Filament table. It encapsulates the column definitions, making them reusable and centralizing the table's structure.
 * By using this schema class, the main Filament Resource remains cleaner, and column configurations can be easily managed and extended.
 *
 * @package App\Filament\Clusters\Blog\Resources\BlogResource\Schema
 */
final readonly class TableSchema
{
    /**
     * Retrieves an array of Filament Table Column components.
     * Each element in the array represents a column to be displayed in the table, configured with its label, sortability, and searchability.
     *
     * @return array An array of Filament Table Column instances.
     */
    public static function getColumnComponents(): array
    {
        return [
            Columns\TextColumn::make('author.name')
                ->label('Auteur')
                ->sortable()
                ->searchable(),
        ];
    }
}
