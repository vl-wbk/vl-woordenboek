<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Schemas;

use App\UserTypes;
use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Spatie\Permission\Models\Role;

final readonly class VolunteerPositionForm
{
    /**
     * @return array<int, TextInput|Select|Textarea|Toggle>
     */
    public static function configure(): array
    {
        return [
            TextInput::make('name')
                ->label('Positie')
                ->placeholder('Redacteur')
                ->columnSpan(3)
                ->unique()
                ->uniqueValidationIgnoresRecordByDefault()
                ->required(),

            TextInput::make('tag_line')
                ->label('Tag line')
                ->maxLength(255)
                ->placeholder('... of sub titel')
                ->columnSpan(9),

            Select::make('role_id')
                ->label('Geassocieerde permissiegroep')
                ->helperText('Bij het goedkeuren van aanmeldingen zal deze permissiegroep automatisch aan de gebruiker worden gekoppeld')
                ->columnSpan(6)
                ->native(false)
                ->relationship('roles', 'name')
                ->createOptionForm(self::createOptionFormSchema())
                ->createOptionAction(fn (Action $action): Action => self::createOptionModalConfiguration($action)),

            Select::make('associated_user_group')
                ->label('Geassocieerde gebruikersgroep')
                ->options(UserTypes::class)
                ->native(false)
                ->helperText('Net het zelfde als bij de gassocieerde permissiegroep zal deze gebruikersgroep aan de groep worden gekoppeld')
                ->columnSpan(6),

            Textarea::make('description')
                ->rows(4)
                ->placeholder('Beschrijf kort wat deze rol inhoud.')
                ->label('Beschrijving')
                ->columnSpanFull()
                ->required()
                ->characterLimit(400),

            Toggle::make('is_open')
                ->onColor('success')
                ->offColor('danger')
                ->columnSpanFull()
                ->label('Gebruikers kunnen zich aanmelden voor deze positie')
        ];
    }

    private static function createOptionModalConfiguration(Action $action): Action
    {
        return $action
            ->visible(auth()->user()->can('create:role'))
            ->modalWidth(Width::Medium)
            ->modalAlignment(Alignment::Center)
            ->modalIcon(Heroicon::OutlinedShieldCheck)
            ->modalIconColor('primary')
            ->modalDescription('Hier kun je een lege permissiegroep aanmaken voor de vrijwilligers positie. Vergeet enkel niet na het aanmaken van de positie deze permissiegroep via het toegangsbeheer aan te vullen met de juiste permissies.')
            ->modalHeading('Permissiegroep aanmaken')
            ->modalFooterActionsAlignment(Alignment::Center);
    }

    /**
     * @return array<int, TextInput>
     */
    private static function createOptionFormSchema(): array
    {
        return [
            TextInput::make('name')
                ->label('Permissiegroep')
        ];
    }
}
