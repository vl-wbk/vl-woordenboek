<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles;

use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Clusters\Cluster;

/**
 * Class ArticlesCluster
 *
 * This cluster serves as the primary organizational container for the "Woordenboek" (Dictionary) module.
 * It groups related resources like Articles, Categories, or Tags into a single sidebar entry to
 * maintain a clean administrative interface and provide a unified breadcrumb context.
 *
 * @package App\Filament\Clusters\Articles
 */
final class ArticlesCluster extends Cluster
{
    use HasActiveIcon;

    /**
     * The Heroicon identifier used to visually represent the dictionary section in the main navigation sidebar
     *
     * @var string|\BackedEnum|null
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-book-open';

    /**
     * The human-readable string displayed in the navigation menu.
     * Set to "Woordenboek" to align the application's Dutch localization.
     *
     * @var string|null
     */
    protected static ?string $navigationLabel = 'Woordenboek';

    /**
     * The text used for this specific segment of the breadcrumb trail.
     * Ensures users have a clear "Home > Woordenboek > ..." path.
     *
     * @var string|null
     */
    protected static ?string $clusterBreadcrumb = 'Woordenboek';

    /**
     * Global access control for the cluster.
     *
     * This method prevents the "Woordenboek" section from appearing in the sidebar if the current user
     * does not have permission to view any of the underlying resources. It prevents the UX friction of clicking a
     * navigation item only to see an empty page.
     *
     * @return bool True if at least one child resource or page accessible.
     */
    public static function canAccess(): bool
    {
        return count((new self)->getSubNavigation()) > 0;
    }
}
