<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ReferenceWorks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;

final readonly class ReferenceWorkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->heading('Naslagwerk formulier')
                    ->description('Via dit formulier kun je een nieuw naslagwerk toevoegen of een bestaan naslagwerk aanpassen.')
                    ->iconColor('primary')
                    ->compact()
                    ->columnSpanFull()
                    ->columns(12)
                    ->schema(self::getSchemaComponents())
            ]);
    }

    private static function getSchemaComponents(): array
    {
        return [
            TextInput::make('abbreviation')
                ->label('Afkorting')
                ->columnSpan(4)
                ->required(),

            TextInput::make('name')
                ->label('Naam')
                ->required()
                ->unique()
                ->columnSpan(8)
                ->maxLength(255),
        ];
    }
}
