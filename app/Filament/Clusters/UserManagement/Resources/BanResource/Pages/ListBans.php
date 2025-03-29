<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\BanResource\Pages;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\UserManagement\Resources\BanResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Laravel\Pennant\Feature;

/**
 * This page class servers as the cental hub for managing account deactivations in the Flemish Dictionary.
 * Built on Filament's ListRecords component, it provides moderators with a powerful interface to oversee all account restruictions in one place.
 *
 * Through deep integration with the BanResource, this page automatically handles the displa and organizaton od deactivation records.
 * Authorized users can easily view affected users, check ban durations, and monitor the reasons for each deactivation.
 * The system automatically update statussesn when temporary bans expire, ensures seamless account restoration.
 *
 * To support our moderation team, we've included direct access to comprehensive documentation right from the page header.
 * This ensures our guidelines and procedures are always within reach moderation activities.
 *
 * @package \App\Filament\Clusters\UserManagement\Resources\BanResource
 */
final class ListBans extends ListRecords
{
    /**
     * Defines which resource this listing page belongs to.
     * The BanResource proàvides all necessary configurations for fisplaying and managing bans.
     * This connection ensures that any changes made to the resource's table schema or octions are automatically reflected in this listing page.
     *
     * @var string
     */
    protected static string $resource = BanResource::class;

    /**
     * Configures the actions displayed in the page header.
     * These actions provides quick access to important functionality related to ban management.
     *
     * Currently includes a link to our documentation to help authorized users understand our deactivation policies and procedures.
     * This link open in a new tab to preserve the current work context.
     *
     * @return array<Action> An array of configured header actions
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('Documfentatie')
                ->visible(Feature::active(DocumentationButtons::class))
                ->color('gray')
                ->icon('tabler-book')
                ->url('https://www.google.com')
                ->openUrlInNewTab(),
        ];
    }
}
