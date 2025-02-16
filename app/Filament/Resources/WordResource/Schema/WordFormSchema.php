<?php

declare(strict_types=1);

namespace App\Filament\Resources\WordResource\Schema;

use App\Enums\LanguageStatus;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Support\Enums\IconSize;

final class WordFormSchema
{
    public static function make(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                //self::generalInfoWizardForm(),
                //self::regionAndStatusInfoWizardForm(),
                self::definitionInfoWizardForm(),
            ])->columnSpan(12),
        ]);
    }

    private static function generalInfoWizardForm(): Step
    {
        return Step::make('Algemene informatie')
            ->icon('heroicon-o-language')
            ->completedIcon('heroicon-m-hand-thumb-up')
            ->columns(12)
            ->schema([
                TextInput::make('woord')
                    ->label('Titel')
                    ->columnSpan(4)
                    ->required()
                    ->maxLength(255),
                TextInput::make('characteristics')
                    ->label('Kenmerken')
                    ->columnSpan(8)
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Beschrijving')
                    ->columnSpan(12)
                    ->cols(2)
                    ->placeholder('De beschrijving van het woord dat je wenst toe te voegen.')
                    ->required(),
                Textarea::make('example')
                    ->label('Voorbeeld')
                    ->placeholder('Probeer zo helder mogelijk te zijn')
                    ->cols(2)
                    ->columnSpan(12)
                    ->required()
                    ->maxLength(255),

            ]);
    }

    private static function regionAndStatusInfoWizardForm(): Step
    {
        return Step::make('Regio & status')
            ->icon('heroicon-o-map')
            ->completedIcon('heroicon-m-hand-thumb-up')
            ->columns(12)
            ->schema([
                Select::make('regions')
                    ->columnSpanFull()
                    ->label("Regio's")
                    ->translateLabel()
                    ->multiple()
                    ->relationship(titleAttribute: 'name')
                    ->optionsLimit(4)
                    ->preload()
                    ->minItems(1)
                    ->required(),
                Radio::make('status')
                    ->columnSpanFull()
                    ->options(LanguageStatus::class)
            ]);
    }

    private static function definitionInfoWizardForm(): Step
    {
        return Step::make('Definities')
            ->icon('heroicon-o-queue-list')
            ->completedIcon('heroicon-m-hand-thumb-up')
            ->schema([
                Repeater::make('definitions')
                    ->label('Definitie voor het woord')
                    ->relationship()
                    ->schema([])
            ]);
    }
}
