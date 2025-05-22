<?php

declare(strict_types=1);

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

/**
 * Represents the settings cluster within the application.
 *
 * This class extends the Filament framework's Cluster functionality to manage the settings related to the application.
 * It configures aspects such as navigation icons, labels, and breadcrumbs specifically for the settings section in the UI.
 *
 * @package App\Filament\Cluster
 */
final class Settings extends Cluster
{
    /**
     * Specifies the icon used in the navigation menu for the settings section.
     *
     * The icon is configured to use a Heroicon's outline design with a specific variant to visually represent this section of the user interface that deals with settings.
     * The chosen icon (`heroicon-o-cog-8-tooth`) visually represents a cogwheel, commonly used to depict settings or configuration options.
     *
     * @var ?string  A nullable string representing the icon name in Heroicon format.
     */
    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';

    /**
     * The label for this cluster that appears in the navigation menu.
     * This label 'Instellingen' translates to 'Settings' in English and is displayed to users in the application's navigation menu as a text identifier for accessing the settings section.
     *
     * @var ?string A nullable string for the navigation label, allowing for dynamic configuration.
     */
    protected static ?string $navigationLabel = 'Instellingen';

    /**
     * Defines the breadcrumb label for the settings cluster in the application's UI.
     *
     * This breadcrumb 'Applicatie instellingen' translates to 'Application settings' in English, providing a clear and contextually relevant cue for users about their location within the application.
     * It helps in maintaining a consistent language and navigational element throughout the application, especially useful in multilingual settings.
     *
     * @var ?string A nullable string containing the breadcrumb label in Dutch.
     */
    protected static ?string $clusterBreadcrumb = 'Applicatie instellingen';
}
