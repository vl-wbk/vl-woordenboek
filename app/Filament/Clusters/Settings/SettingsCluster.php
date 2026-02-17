<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Settings;

use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Clusters\Cluster;

/**
 * Represents the settings cluster within the app.
 *
 * This class extends the Filament framework's Cluster capability to manage the settings related to the app.
 * It configures aspects such as navigation icons, labels, and breadcrumbs specifically for the settings section in the UI.
 *
 * @package App\Filament\Cluster
 */
final class SettingsCluster extends Cluster
{
    use HasActiveIcon;

    /**
     * Specifies the icon used in the navigation menu for the settings section.
     *
     * The icon is configured to use a Heroicon's outline design with a specific variant to visually represent this section of the user interface that deals with settings.
     * The chosen icon (`heroicon-o-cog-8-tooth`) visually represents a cogwheel, commonly used to depict settings or configuration options.
     *
     * @var ?string  A nullable string representing the icon name in Heroicon format.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    /**
     * The label for this cluster that appears in the navigation menu.
     * This label 'Instellingen' translates to 'Settings' in English and is displayed to users in the app's navigation menu as a text identifier for accessing the settings section.
     *
     * @var ?string A nullable string for the navigation label, allowing for dynamic configuration.
     */
    protected static ?string $navigationLabel = "Pagina's";

    /**
     * Defines the breadcrumb label for the settings cluster in the app's UI.
     *
     * This breadcrumb 'Applicatie instellingen' translates to 'Application settings' in English, providing a clear and contextually relevant cue for users about their location within the app.
     * It helps in maintaining a consistent language and navigational element throughout the app, especially useful in multilingual settings.
     *
     * @var ?string A nullable string containing the breadcrumb label in Dutch.
     */
    protected static ?string $clusterBreadcrumb = "Pagina's";

    public static function canAccess(): bool
    {
        return count((new self)->getSubNavigation()) > 0;
    }
}
