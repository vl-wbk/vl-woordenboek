<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog;

use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Clusters\Cluster;

/**
 * Represents a custom Filament cluster encapsulating capability specific to the "Nieuwsberichten" or Blog module in the app.
 *
 * This class extends Filament's `Cluster` base capability and integrates custom features concerning blog/news content management.
 * The purpose of this cluster is to group related pages and resources under a unified navigation item in the Filament Admin Panel, improving accessibility and organization for administrators.
 *
 * Features such as permission handling via `HasPageShield` ensure that access to this cluster is secure and highly configurable, allowing differentiated access levels for users interacting with the blog module.
 *
 * @package App\Filament\Clusters
 */
final class BlogCluster extends Cluster
{
    use HasActiveIcon;

    /**
     * Defines the navigation icon that represents this cluster in the administrator panel sidebar.
     *
     * This property sets the visual marker for the cluster using the `heroicon-o-newspaper` icon, which is a standard icon design intuitively linked to news or blog content.
     * This improves the visual clarity and accessibility of the administrator panel, enabling fast user recognition.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-newspaper';

    /**
     * Sets the label shown in the navigation sidebar for this cluster.
     *
     * The label, `Nieuwsberichten`, is written in Dutch and reflects the primary language of the administrative users.
     * This label helps admins identify the purpose of this cluster—to manage posted news and blog articles—at a glance.
     *
     * Example Use Case:
     * when accessing the administrator panel, an administrator will see this menu item under the navigation area, clicking which expands or redirects to news-related pages.
     */
    protected static ?string $navigationLabel = 'Nieuwsberichten';

    /**
     * Defines the string displayed in the breadcrumb navigation for this cluster.
     *
     * Breadcrumbs are essential for providing contextual navigation within the administrator panel, particularly in multi-level navigation hierarchies like those involving clusters and individual news pages.
     * The `Nieuwsberichten` breadcrumb reinforces the user's current position in the interface and aids in navigation.
     */
    protected static ?string $clusterBreadcrumb = 'Nieuwsberichten';

    public static function canAccess(): bool
    {
        return count((new self)->getSubNavigation()) > 0;
    }
}
