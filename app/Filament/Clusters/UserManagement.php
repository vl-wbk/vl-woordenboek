<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

final class UserManagement extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Gebruikersbeheer';
    protected static ?string $clusterBreadcrumb = 'Gebruikersbeheer';
}
