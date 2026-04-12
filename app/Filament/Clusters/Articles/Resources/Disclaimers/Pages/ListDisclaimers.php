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

/**
 * Represents the page for listing disclaimer records in the admin panel.
 *
 * The 'ListDisclaimers' class Filament's 'ListRecords' class to provide a comprehensive overview of all disclaimers.
 * It is a component of the 'DisclaimerResource' within the Articles cluster and facilitates high-level management tasks.
 *
 * This page allows administrators to browse existing disclaimers, access external documentation for guidance,
 * and utilize actions for creating new records or generating test data via factories.
 *
 * @package App\Filament\Clusters\articles\Resources\Disclaimers\Pages
 */
final class ListDisclaimers extends ListRecords
{
    /**
     * Specifies the resource associated with this page.
     *
     * This property links the 'ListDisclaimers' page to the 'DisclaimerResource', ensuring that
     * the correct model and table configurations are utilized for the record listing.
     */
    protected static string $resource = DisclaimerResource::class;

    /**
     * Defines the actions displayed in the header
     *
     * The header actions provide essential tools for the disclaimer management workflow. This includes
     * a conditional help button for documentation and a grouped set of actions for record instantiation.
     *
     * @return array<Action|ActionGroup> An array of configured header actions.
     */
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

                // Factory action
                // ---
                // This action is specifically included to assist developers and testers in generating
                // mock disclaimer data directly from the UI, ensuring the dictionary stay populated during testing.
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
