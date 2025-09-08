<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;

use App\Enums\DisclaimerTypes;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Support\Enums\IconSize;

/**
 * @todo Document this class
 */
final readonly class FormSchema
{
    private static function createSection(string $title, string $icon, string $description): Section
    {
        return Section::make($title)
            ->icon($icon)
            ->iconSize(IconSize::Medium)
            ->iconColor('primary')
            ->description($description)
            ->collapsible()
            ->columns(12)
            ->compact();
    }

    public static function configure(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
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
            ]);
    }

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

    private static function getManagementInformationSchema(): array
    {
        return [
            TextInput::make('name')
                ->label(label: __('disclaimer-resource.form.sections.management-info..fields.name'))
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
}
