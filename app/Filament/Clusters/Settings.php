<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Settings extends Cluster
{
    /**
     * The icon shown in the navigation menu.
     * We use the Heroicon users outline variant to represent this user management section visually.
     */
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    /**
     * @todo Document this
     */
    protected static ?string $navigationLabel = 'Instellingen';

    /**
     * The Dutch label displayed in the navigation menu.
     * This keeps our interface language consistent throughout the application.
     */
    protected static ?string $clusterBreadcrumb = 'Applicatie instellingen';
}
