<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;

use App\Enums\Articles\EtymologyStatus;
use App\Enums\Articles\EtymologyTypes;
use Filament\Forms\Components\{DatePicker, Select, TextArea, TextInput};
use Filament\Forms\Form;

final readonly class FormSchema
{
    public static function configure(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema(self::configureColumns());
    }

    public static function configureColumns(): array
    {
        return [
            Select::make('status')
                    ->label('Status van de gegevens')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->options(EtymologyStatus::class)
                    ->native(false)
                    ->required(),

                Select::make('type')
                    ->label('Etymologisch type')
                    ->translateLabel()
                    ->options(EtymologyTypes::class)
                    ->required()
                    ->searchable()
                    ->native(false)
                    ->columnSpan(3),

                TextInput::make('origin_language')
                    ->label('Taal van oorsprong')
                    ->translateLabel()
                    ->columnSpan(3)
                    ->required()
                    ->maxLength(255),

                TextInput::make('origin_form')
                    ->label('Vorm in de brontaal')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),

                DatePicker::make('period_start')
                    ->label('Periode (start)')
                    ->translateLabel()
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                DatePicker::make('period_end')
                    ->label('Periode (einde)')
                    ->translateLabel()
                    ->required()
                    ->native(false)
                    ->columnSpan(6),

                Textarea::make('etymology')
                    ->label('Beschrijving van de herkomst')
                    ->translateLabel()
                    ->columnSpanFull()
                    ->required(),

                Textarea::make('note')
                    ->label('Interne notitie voor administratieve doeleinden')
                    ->translateLabel()
                    ->columnSpanFull(),

                TextInput::make('source')
                    ->label('Bron notitie')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(6),

                TextInput::make('source_url')
                    ->label('Hyperlink van de bron')
                    ->translateLabel()
                    ->maxLength(255)
                    ->columnSpan(6),
        ];
    }
}
