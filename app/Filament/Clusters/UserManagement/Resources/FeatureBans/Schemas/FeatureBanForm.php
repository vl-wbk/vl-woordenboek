<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\FeatureBans\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

final readonly class FeatureBanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                Select::make('user_id')
                    ->label('Gebruiker')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->columnSpan(4)
                    ->required(),

                Select::make('feature')
                    ->label('Functionaliteit')
                    ->native(false)
                    ->options(config('flemish-dictionary.features.bannable'))
                    ->columnSpan(4)
                    ->required(),

                DateTimePicker::make('expires_at')
                    ->label('Verloopt op')
                    ->helperText('Laat dit leeg voor een permanente ban.')
                    ->columnSpan(4)
                    ->native(false)
                    ->after('now'),

                Textarea::make('reason')
                    ->label('Motivering')
                    ->rows(5)
                    ->placeholder('Motiveer kort waarom de gebruiker de beperking opgelegd krijgt.')
                    ->columnSpanFull(),
            ]);
    }
}
