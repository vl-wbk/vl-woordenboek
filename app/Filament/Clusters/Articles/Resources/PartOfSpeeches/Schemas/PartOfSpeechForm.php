<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final readonly class PartOfSpeechForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextInput::make('value')
                    ->columnSpan(3)
                    ->label(label: __('Afkorting')),
                TextInput::make('name')
                    ->label(label: __('Woordsoort'))
                    ->columnSpan(9),
                Toggle::make('suggestible')
                    ->columnSpan(12)
                    ->label('Deze woordsoort kan gebruikt worden in het suggestieformulier'),
            ]);
    }
}
