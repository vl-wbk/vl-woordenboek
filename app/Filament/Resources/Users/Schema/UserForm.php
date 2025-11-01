<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Schema;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use App\UserTypes;
use Filament\Forms\Components;
use Filament\Support\Icons\Heroicon;

/**
 * UserForm schema configuration class.
 *
 * This class defines the form structure for the user resource in Filament.
 * It provides a centralized configuration for creating and editing user records, including fields for user type,
 * credentials, personal information, and role assignments.
 *
 * The form is designed to work with Laravel's authorization and validation systems, with centrain fields being
 * conditionally disabled based on the form context (create vs. edit).
 */
final readonly class UserForm
{
    /**
     * Configures and returns the user form schema.
     *
     * This method builds a complete form schema with a single section containing
     * all necessary input fields for user management. The form includes:
     *
     * - User type selection (required)
     * - Username (required, unique, disabled on edit)
     * - First name (required)
     * - Last name (required)
     * - Email address (required, unique, validated)
     * - Role assignments (multiple selection with constraints)
     *
     * Field behavior:
     *
     * - The username field is disabled in edit mode to prevent changes
     * - All fields marked as required must be filled before submission
     * - Unique validation is applied to username and email, ignoring the current record on updates
     * - Role selection is limited to a maximum of 6 items
     *
     * @param  Schema $schema   The base schema instance to be configured
     * @return Schema           The configured schema with all form components
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(heading: __('user-resource.form.section.heading'))
                    ->description(description: __('user-resource.form.section.description'))
                    ->icon(Heroicon::OutlinedUserPlus)
                    ->iconColor('primary')
                    ->compact()
                    ->columnSpanFull()

                    ->schema([
                        Components\Select::make('user_type')
                            ->label(label: __('user-resource.form.section.inputs.user_type'))
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
                            ->label(label: __('user-resource.form.section.inputs.firstname'))
                            ->required()
                            ->columnSpan(3),

                        Components\TextInput::make('lastname')
                            ->label(label: __('user-resource.form.section.inputs.lastname'))
                            ->required()
                            ->columnSpan(3),

                        Components\TextInput::make('email')
                            ->label(label: __('user-resource.form.section.inputs.email'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->email()
                            ->columnSpan(12),

                        Components\Select::make('roles')
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
