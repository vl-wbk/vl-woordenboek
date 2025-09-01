<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schema;

use App\UserTypes;
use Filament\Forms\Form;
use Filament\Forms\Components;

final readonly class UserForm
{
    public static function configure(Form $form): Form
    {
        return $form
            ->schema([
                Components\Section::make('Nieuwe gebruiker aanmaken')
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('primary')
                    ->description('Vul hier alle benodigde informatie in voor het aanmaken van een nieuwe gebruiker op het Vlaams woordenboek')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        Components\Select::make('user_type')
                            ->label('Gebruikersgroep')
                            ->required()
                            ->native(false)
                            ->options(UserTypes::class)
                            ->columnSpan(3)
                            ->required(),
						Components\TextInput::make('name')
							->label('Gebruikersnaam')
							->required()
							->placeholder('- niet opgegeven')
							->disabledOn('edit')
							->unique(ignoreRecord: true)
							->columnSpan(3),
                        Components\TextInput::make('firstname')
                            ->label('Voornaam')
                            ->required()
                            ->columnSpan(3),
                        Components\TextInput::make('lastname')
                            ->label('Achternaam')
                            ->required()
                            ->columnSpan(3),
                        Components\TextInput::make('email')
                            ->label('E-mail adres')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->columnSpan(12),
                        Components\Select::make('roles')
                            ->label('Permissie groepen')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->columnSpanFull()
                            ->maxItems(6)
                            ->maxItemsMessage(__('Er kunnen maar maximum 3 permissie groepen voor een gebruiker geslecteerd worden.'))
                            ->helperText('Deze groepen bepalen wie tot welke zaken toegang heeft in het vlaams woordenboek. Laat dit leeg als het om het gewone gebruiker gaat die het woordenboek enkel bezoekt.')
                            ->searchable(),
                    ]),
            ]);
    }
}
