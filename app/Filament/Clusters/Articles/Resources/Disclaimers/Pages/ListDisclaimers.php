<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers\Pages;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\Articles\Resources\Disclaimers\DisclaimerResource;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Laravel\Pennant\Feature;

final class ListDisclaimers extends ListRecords
{
    protected static string $resource = DisclaimerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('help')
                ->label(label: __('buttons.help'))
                ->visible(Feature::active(DocumentationButtons::class))
                ->url('https://vl-wbk.github.io/documentatie/artikelen/disclaimers', shouldOpenInNewTab: true)
                ->icon('heroicon-o-lifebuoy'),

            ActionGroup::make([
                CreateAction::make()
                    ->label(label: __('disclaimer-resource.header-actions.create.label'))
                    ->color('gray')
                    ->icon('heroicon-o-plus-circle'),
                FactoryAction::make()
                    ->color('gray')
                    ->hiddenLabel()
                    ->modalIcon(Heroicon::OutlinedCog8Tooth)
                    ->modalIconColor('primary')
                    ->modalHeading('Genereer disclaimers')
                    ->modalDescription('Genereer test disclaimers voor het woordenboek, deze kunnen worden gebruikt om te testen of de applicatie werkt zoals verwacht.'),
            ])->buttonGroup(),
        ];
    }
}
