<?php

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReferenceWorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('abbreviation'),
                TextInput::make('name')
                    ->required(),
            ]);
    }
}
