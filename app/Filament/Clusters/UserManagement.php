<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Clusters\Cluster;

/**
 * All user-related administrative features for the Flemish dictionary app are organized within this cluster.
 * It serves as a container for resources like user accounts, deactivations, and other user-related management tools.
 *
 * The interface is provided in Dutch to align with the community's primary language.
 * You'll see this reflected in the navigation labels and breadcrumbs.
 * The cluster uses a users icon from the Heroicon set to maintain visual consistency with other parts of the administrator panel.
 *
 * When extending this cluster, remember that all child resources will inherit these navigation settings.
 * It's a great place to add new user management features while keeping everything neatly organized.
 *
 * @package App\Filament\Clusters
 */
final class UserManagement extends Cluster
{
    use HasPageShield;

    /**
     * The icon shown in the navigation menu.
     * This user management section is visually represented by the Heroicon users outline variant.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-users';

    /**
     * The Dutch label displayed in the navigation menu.
     * This approach ensures the interface language remains consistent throughout the app.
     */
    protected static ?string $navigationLabel = 'Gebruikersbeheer';

    /**
     * The Dutch text shown in the breadcrumb trail.
     * This helps administrators understand their current location in the administrator interface.
     */
    protected static ?string $clusterBreadcrumb = 'Gebruikersbeheer';
}
