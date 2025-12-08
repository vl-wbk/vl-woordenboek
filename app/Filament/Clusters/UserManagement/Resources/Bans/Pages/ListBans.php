<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\Bans\Pages;

use App\Features\DocumentationButtons;
use App\Filament\Clusters\UserManagement\Resources\Bans\BanResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Laravel\Pennant\Feature;

/**
 * This page class servers as the cental hub for managing account deactivations in the Flemish Dictionary.
 * Built on Filament's ListRecords component, it provides moderators with a powerful interface to oversee all account restrictions in one place.
 *
 * Through deep integration with the BanResource, this page automatically handles the display and organization od deactivation records.
 * Authorized users can easily view affected users, check ban durations, and monitor the reasons for each deactivation.
 * The system automatically update statuses when temporary bans expire, ensures seamless account restoration.
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
     * The BanResource provides all necessary configurations for displaying and managing bans.
     * This connection ensures that any changes made to the resource's table schema or actions are automatically reflected in this listing page.
     */
    protected static string $resource = BanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('docs')
                ->label('Help')
                ->visible(Feature::active(DocumentationButtons::class))
                ->icon(Heroicon::OutlinedLifebuoy)
                ->url('https://www.google.com')
                ->openUrlInNewTab(),
        ];
    }
}
