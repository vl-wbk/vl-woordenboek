<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Schema;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Enums\DisclaimerTypes;
use Filament\Forms\Components\{Select, Textarea, TextInput};
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;

/**
 * FormSchema
 * 
 * This class orchestrates the input architecture for the Disclaimer resource.
 * It is designed to be highly modular, allowing future maintainers to update specific 
 * form segments (Public Info, Management Metadata, or Internal Notices) without disrupting the overall layout.
 * 
 * To ensure consistency across the application, all form sections are generated via 
 * the `createSection` helper, enforcing a unified look for headers, icons, and spacing.
 *
 * @package App\Filament\Clusters\Articles\Resources\Disclaimers\Schema
 */
final readonly class FormSchema
{
    /**
     * Standardized Section Factory
     * 
     * Enforces a consistent UI pattern for form sections. Use this method to maintain 
     * visual harmony; it sets default primary colors, large icons, and compact styling.
     *
     * @param  string          $title        Localized title of the section.
     * @param  string|Heroicon $icon         The Heroicon identifier or Outlined enum.
     * @param  string          $description  Localized instructional text for the user.
     * @return Section                       A pre-configured Filament Section component.
     */
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

    /**
     * Main Configuration Entry Point
     * 
     * This method builds the complete form schema by assembling individual sections. 
     * When extending the model with new fields, identify the logical section below and update the corresponding private method.
     *
     * @param  Schema $schema  The base Filament schema container.
     * @return Schema          The fully assembled form schema.
     */
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
     * Public Content Schema
     * Manages fields that are directly exposed to the end-user or impact the frontend rendering, such as the disclaimer type and the actual text message.
     * 
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
     * Administrative Metadata Schema
     * 
     * Manages internal-only tracking data. This includes the unique identifier name,  functional descriptions, 
     * and usage instructions to help other admins understand the "why" and "where" of this specific record.
     * 
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
     * Internal Editorial Schema
     * 
     * Handles specific overrides and notices for content editors. 
     * These fields allow for granular control over how the disclaimer is described within the CMS, providing fallback logic if fields are left empty.
     * 
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
