<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

final class Blog extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Updates';

    protected static ?string $clusterBreadcrumb = 'Updates';
}
