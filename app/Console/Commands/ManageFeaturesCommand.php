<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Laravel\Pennant\Feature;

/**
 * ManageFeaturesCommand is a console command for managing feature flags in the application.
 *
 * This command provides administrators and developers with the ability to enable, disable,
 * or check the status of feature flags. It integrates with Laravel Pennant to manage
 * feature toggles globally and ensures that feature states are consistently applied
 * across the application.
 *
 * The command accepts two arguments:
 * - `action`: Specifies the operation to perform (enable, disable, or status).
 * - `feature`: The name of the feature flag to manage.
 *
 * Key Features:
 * - Enables feature flags globally for all users.
 * - Disables feature flags globally for all users.
 * - Checks the current status of a feature flag.
 * - Resolves feature class names dynamically to ensure proper namespace handling.
 *
 * @package App\Console\Commands
 */
final class ManageFeaturesCommand extends Command
{
    /**
     * The signature of the console command.
     *
     * This defines the command's name and its required arguments.
     * The `action` argument specifies the operation to perform (enable, disable, or status), and the `feature` argument specifies the feature flag to manage.
     *
     * @var string
     */
    protected $signature = 'feature:manager {action} {feature}';

    /**
     * The description of the console command.
     * This provides a brief explanation of what the command does, which is displayed when running `php artisan list` or `php artisan help feature:manager`.
     *
     * @var string
     */
    protected $description = 'Enable, disable or check the status of a feature flag';

    /**
     * Handles the execution of the command.
     *
     * This method processes the `action` and `feature` arguments, resolves the fully qualified class name of the feature, and performs the requested action.
     * It uses a `match` expression to delegate the action to the appropriate method.
     *
     * If the feature class does not exist, an error message is displayed, and the command terminates.
     * After performing the action, the feature cache is flushed to ensure the changes take effect immediately.
     *
     * @return void
     */
    public function handle(): void
    {
        $action = $this->argument("action");
        $feature = $this->argument('feature');

        $fullyQualifiedFeatureClass = $this->resolveFeatureClass($feature);

        if ( ! class_exists($fullyQualifiedFeatureClass)) {
            $this->error("The feature class '{$fullyQualifiedFeatureClass}' does not exist.");
            return;
        }

        match ($action) {
            'enable' => $this->enableFeature($fullyQualifiedFeatureClass),
            'disable' => $this->disableFeature($fullyQualifiedFeatureClass),
            'status' => $this->featureStatus($fullyQualifiedFeatureClass),
            default => $this->error('Invalid action. Use "enable", "disable", or "status"'),
        };

        Feature::flushCache();
    }

    /**
     * Enables a feature flag globally for all users.
     *
     * This method activates the specified feature flag for everyone in the application.
     * It uses Laravel Pennant's `activateForEveryone` method to ensure the feature is enabled globally.
     * A success message is displayed upon completion.
     *
     * @param  string $feature  The fully qualified class name of the feature to enable.
     * @return void
     */
    protected function enableFeature(string $feature, ?string $scope = null): void
    {
        Feature::activateForEveryone($feature);
        $this->info("Feature '{$feature}' enabled globally.");
    }

    /**
     * Disables a feature flag globally for all users.
     *
     * This method deactivates the specified feature flag for everyone in the application.
     * It uses Laravel Pennant's `deactivateForEveryone` method to ensure the feature is disabled globally.
     * A success message is displayed upon completion.
     *
     * @param string $feature The fully qualified class name of the feature to disable.
     * @return void
     */
    protected function disableFeature(string $feature): void
    {
        Feature::deactivateForEveryone($feature);
        $this->info("Feature '{$feature}' disabled globally.");
    }

    /**
     * Checks the current status of a feature flag.
     *
     * This method determines whether the specified feature flag is currently active or inactive.
     * It uses Laravel Pennant's `active` method to check the feature's status and displays the result as a message.
     *
     * @param  string $feature  The fully qualified class name of the feature to check.
     * @return void
     */
    protected function featureStatus(string $feature): void
    {
        if (Feature::active($feature)) {
            $this->info("Feature '{$feature}' is active.");
        } else {
            $this->info("Feature '{$feature}' is inactive.");
        }
    }

    /**
     * Resolves the fully qualified class name of a feature.
     *
     * This method ensures that the feature class name is properly namespaced.
     * If the provided feature name does not already include the namespace, it prepends the default namespace (`App\Features\`) to the feature name.
     * The feature name is also converted to StudlyCase to match class naming conventions.
     *
     * @param  string $featureClass  The feature name provided by the user.
     * @return string                The fully qualified class name of the feature.
     */
    private function resolveFeatureClass(string $featureClass): string
    {
        $featureNamespace = 'App\\Features\\';

        return Str::startsWith($featureClass, $featureNamespace)
            ? $featureClass
            : $featureNamespace . Str::studly($featureClass);
    }
}
