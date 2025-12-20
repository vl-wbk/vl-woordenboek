<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Enums\DisclaimerTypes;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;

/**
 * @todo Document this class
 */
final readonly class FormSchema
{
    private static function createSection(string $title, string|Heroicon $icon, string $description): Section
    {
        return Section::make($title)
            ->icon($icon)
            ->iconSize(IconSize::Large)
            ->iconColor('primary')
            ->description($description)
            ->collapsible()
            ->columnSpanFull()
            ->compact();
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                self::createSection(
                    title: __('disclaimer-resource.form.sections.disclaimer-info.title'),
                    icon: 'heroicon-o-wrench-screwdriver',
                    description: __('disclaimer-resource.form.sections.disclaimer-info.description'),
                )->schema(self::getDisclaimerInformationSchema()),

                self::createSection(
                    title: __('disclaimer-resource.form.sections.management-info.title'),
                    icon: 'heroicon-o-information-circle',
                    description: __('disclaimer-resource.form.sections.management-info.description'),
                )->schema(self::getManagementInformationSchema()),

                self::createSection(
                    title: 'Interne weergave van de disclaimer',
                    icon: Heroicon::OutlinedInformationCircle,
                    description: 'Configureer de interne weergave van een disclaimer met een titel en redactiemelding',
                )->columns(12)->schema(self::getInternalDisclaimerSchema())
            ]);
    }

    /**
     * @return array<int, Select|Textarea>
     */
    private static function getDisclaimerInformationSchema(): array
    {
        return [
            Select::make('type')
                ->columnSpan(6)
                ->required()
                ->options(DisclaimerTypes::class)
                ->native(false),
            Textarea::make('message')
                ->label(label: __('disclaimer-resource.form.sections.disclaimer-info.fields.message.label'))
                ->required()
                ->placeholder(placeholder: __('disclaimer-resource.form.sections.disclaimer-info.fields.message.placeholder'))
                ->columnSpan(12)
                ->rows(2),
        ];
    }

    /**
     * @return array<int, TextInput|Textarea>
     */
    private static function getManagementInformationSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(label: __('disclaimer-resource.form.sections.management-info.fields.name'))
                ->maxLength(255)
                ->required()
                ->unique(ignoreRecord: true)
                ->columnSpan(8),
            Textarea::make('description')
                ->label(label: __('disclaimer-resource.form.sections.management-info.fields.description.label'))
                ->required()
                ->placeholder(placeholder: __('disclaimer-resource.form.sections.management-info.fields.description.placeholder'))
                ->columnSpan(12)
                ->rows(3),
            Textarea::make('usage')
                ->label(label: __('disclaimer-resource.form.sections.management-info.fields.usage.label'))
                ->required()
                ->placeholder(placeholder: __('disclaimer-resource.form.sections.management-info.fields.usage.placeholder'))
                ->columnSpan(12)
                ->rows(3),
        ];
    }

    /**
     * @return array<TextInput|Textarea>
     */
    private static function getInternalDisclaimerSchema(): array
    {
        return [
            TextInput::make('internal_title')
                ->columnSpan(12)
                ->label('Titel')
                ->maxLength(255)
                ->hint('Indien dit veld leeg is zal de algemene titel van de disclaimer worden weergegeven'),
            Textarea::make('internal_message')
                ->label(label: 'Interne redactiemelding')
                ->required()
                ->placeholder(placeholder: 'Beschrijf kort waarvoor deze melding staat en waarop de redacteur moet letten.')
                ->hint('Indien dit tekstvak leeg blijft zal de algemene melding van de disclaimer worden gebruikt')
                ->columnSpan(12)
                ->rows(3),
        ];
    }
}
