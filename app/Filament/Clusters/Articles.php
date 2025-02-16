<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

final class Articles extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Woordenboek';
    protected static ?string $clusterBreadcrumb = 'Woordenboek';
}
