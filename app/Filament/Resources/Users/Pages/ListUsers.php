<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Features\DocumentationButtons;
use Asmit\ResizedColumn\HasResizableColumn;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\ListRecords;
use Laravel\Pennant\Feature;

/**
 * Retrieves the header widgets to be displayed on the "List Users" page.
 * This method delegates to the `UserResource` to get any widgets defined for its header, allowing for custom components or summaries to be shown preceding the main record listing.
 *
 * @return array An array of header widget classes.
 */
final class ListUsers extends ListRecords
{
    /**
     * Specifies the resource class this listing bpage belongs to.
     * This association embers proper routing an resource management within the filament aadmin panel structure.
     *
     * @package App\Filament\Resources\UserResource/Pages
     */
    protected static string $resource = UserResource::class;

    /**
     * Retrieves the header widgets to be displayed on the "List Users" page.
     *
     * This method delegates to the `UserResource` to get any widgets defined for its header,
     * allowing for custom components or summaries to be shown preceding the main record listing.
     *
     * @return array<class-string> An array of header widget classes.
     */
    protected function getHeaderWidgets(): array
    {
        return UserResource::getWidgets();
    }

    /**
     * Retrieves the array of actions and action groups to be displayed in the header of the list page.
     *
     * This method defines the primary interactive elements (buttons) that appear in the
     * header area of the list page, allowing users to perform various tasks.
     *
     * The returned array typically includes:
     * 1. A standalone 'documentation-reference' Action for help or external links.
     * 2. An ActionGroup containing common creation and utility actions like:
     * - A 'CreateAction' for adding a new record.
     * - A 'FactoryAction' for generating dummy data, presented as a button group.
     *
     * @return array<int, \Filament\Actions\Action | \Filament\Actions\ActionGroup> An array of Filament actions and action groups.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('documentation-reference')
                ->visible(Feature::active(DocumentationButtons::class))
                ->icon(Heroicon::OutlinedLifebuoy)
                ->label(label: __('buttons.help')),

            ActionGroup::make([
                CreateAction::make()
                    ->label(label: __('user-resource.buttons.create-user'))
                    ->color('gray')
                    ->icon('heroicon-o-user-plus'),
                FactoryAction::make()->color('gray')
                    ->modalHeading(heading: __('user-resource.actions.generate.heading'))
                    ->modalDescription(description: __('user-resource.actions.generate.description'))
            ])->buttonGroup(),
        ];
    }
}
