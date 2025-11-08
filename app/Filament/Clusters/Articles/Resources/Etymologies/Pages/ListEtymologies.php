<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Etymologies\Pages;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\Articles\Resources\Etymologies\EtymologyResource;
use App\Models\Article;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ListRecords;
use Laravel\Pennant\Feature;

/**
 * Represents the Filament page for listing Etymology records.
 *
 * This class extends Filament's `ListRecords` base class, providing the functionality to display a paginated list of all Etymology entries.
 * It integrates with the `EtymologyResource` to fetch and render the data, and also includes any widgets defined for the Etymology resource in its header.
 * This page serves as the primary interface for users to browse and manage etymology records.
 *
 * @package App\Filament\Clusters\Articles\Resources\EtymologyResource\Pages
 */
final class ListEtymologies extends ListRecords
{
    /**
     * The resource associated with this list page.
     *
     * This static property links the `ListEtymologies` page to the `EtymologyResource`.
     * It ensures the page correctly displays and manages data for Etymology models, providing a seamless interface for handling etymological records.
     *
     * @var string $resource - The fully qualified class name of the resource.
     */
    protected static string $resource = EtymologyResource::class;

    /**
     * Retrieves the array of widgets to be displayed in the header of the list page.
     *
     * This method delegates to the `EtymologyResource::getWidgets()` method to obtain the widgets configured for the Etymology resource.
     * These widgets typically provide summary statistics or other relevant information at the top of the etymology list.
     *
     * @return array<mixed> An array of Filament widgets.
     */
    protected function getHeaderWidgets(): array
    {
        return EtymologyResource::getWidgets();
    }

    /**
     * Retrieves the array of actions to be displayed in the header of the list page.
     *
     * This method defines and returns an array of Filament `Action` objects, which appear
     * as buttons or links in the header area of the list page. In this case, it defines
     * a 'help' action that links to an external resource.
     *
     * @return array<int, Action|ActionGroup> An array of Filament action objects.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('help')
                ->label(label: __('buttons.help'))
                ->translateLabel()
                ->icon('heroicon-o-lifebuoy')
                ->visible(Feature::active(DocumentationButtons::class))
                ->url('https://www.google.com', shouldOpenInNewTab: true),

            ActionGroup::make([
                FactoryAction::make()
                    ->label('Genereer etymologieën')
                    ->hiddenLabel(false)
                    ->visible(fn(): bool => Article::query()->count() > 0)
                    ->modalHeading('Genereer etymologieën')
                    ->modalDescription('Genereer etymologieën van artikelen om de functionaliteit(en) te testen in je lokale omgeving.')
            ])->buttonGroup()
        ];
    }
}
