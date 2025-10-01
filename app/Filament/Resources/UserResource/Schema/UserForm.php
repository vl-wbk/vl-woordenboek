<?php

declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Schema;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\UserTypes;
use Filament\Forms\Components;

/**
 * @todo Document this class
 */
final readonly class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(heading: __('user-resource.form.section.heading'))
                    ->description(description: __('user-resource.form.section.description'))
                    ->icon('heroicon-o-user-plus')
                    ->iconColor('primary')
                    ->compact()
                    ->columns(12)

                    ->schema([
                        Select::make('user_type')
                            ->label(label: __('user-resource.form.section.inputs.user_type'))
                            ->required()
                            ->native(false)
                            ->options(UserTypes::class)
                            ->columnSpan(3)
                            ->required(),
                        TextInput::make('name')
                            ->label('Gebruikersnaam')
                            ->required()
                            ->placeholder('- niet opgegeven')
                            ->disabledOn('edit')
                            ->unique(ignoreRecord: true)
                            ->columnSpan(3),

                        TextInput::make('firstname')
                            ->label(label: __('user-resource.form.section.inputs.firstname'))
                            ->required()
                            ->columnSpan(3),

                        TextInput::make('lastname')
                            ->label(label: __('user-resource.form.section.inputs.lastname'))
                            ->required()
                            ->columnSpan(3),

                        TextInput::make('email')
                            ->label(label: __('user-resource.form.section.inputs.email'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->columnSpan(12),

                        Select::make('roles')
                            ->label(label: __('user-resource.form.section.inputs.roles.label'))
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->columnSpanFull()
                            ->maxItems(6)
                            ->maxItemsMessage(message: __('user-resource.form.section.inputs.roles.max_items_message', ['max' => '3']))
                            ->helperText(text: __('user-resource.form.section.inputs.roles.helper_text'))
                            ->searchable(),
                    ]),
            ]);
    }
}
