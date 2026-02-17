<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles;

use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Clusters\Cluster;

/**
 * @todo Document cluster
 */
final class ArticlesCluster extends Cluster
{
    use HasActiveIcon;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Woordenboek';
    protected static ?string $clusterBreadcrumb = 'Woordenboek';

    public static function canAccess(): bool
    {
        return count((new self)->getSubNavigation()) > 0;
    }
}
