<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Clusters\Cluster;

/**
 * @todo Document this cluster
 */
final class Blog extends Cluster
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Nieuwsberichten';

    protected static ?string $clusterBreadcrumb = 'Nieuwsberichten';
}
