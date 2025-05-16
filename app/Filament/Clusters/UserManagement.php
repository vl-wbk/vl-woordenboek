<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * This cluster organizes all user-related administrative features in our Flemisch dictionary application.
 * It serves as a container for resources like user accounts, deactivations, and other user-related management tools.
 *
 * The interface is entirely in Dutch, matching our community's primary language.
 * You'll see this reflected in the navigation labels and breadcrumbs.
 * The cluster uses a users icon from the Heroicon set to maintain visual consistency with other parts of the admin panel.
 *
 * When extending this cluster, remember that all child resources will inherit these navigation settings.
 * It's a great place to add new user management features while keeping everything neatly organized.
 *
 * @package App\Filament\Clusters
 */
final class UserManagement extends Cluster
{
    /**
     * The icon shown in the navigation menu.
     * We use the Heroicon users outline variant to represent this user management section visually.
     */
    protected static ?string $navigationIcon = 'heroicon-o-users';

    /**
     * The Dutch label displayed in the navigation menu.
     * This keeps our interface language consistent throughout the application.
     */
    protected static ?string $navigationLabel = 'Gebruikersbeheer';

    /**
     * The Dutch text shown in the breadcrumb trail.
     * This helps administrators understand their current location in the admin interface.
     */
    protected static ?string $clusterBreadcrumb = 'Gebruikersbeheer';
}
