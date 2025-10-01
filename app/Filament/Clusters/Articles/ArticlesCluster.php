<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles;

use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Clusters\Cluster;

/**
 * @todo Document cluster
 */
final class ArticlesCluster extends Cluster
{
    use HasPageShield;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Woordenboek';
    protected static ?string $clusterBreadcrumb = 'Woordenboek';
}
