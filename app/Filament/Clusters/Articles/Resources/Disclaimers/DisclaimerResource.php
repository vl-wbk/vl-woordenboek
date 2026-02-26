<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Disclaimers;

use App\Filament\Clusters\Articles\Resources\Disclaimers\RelationManagers\ArticlesRelationManager;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Schema\FormSchema;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Schema\InfolistSchema;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Schema\TableSchema;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Pages\ListDisclaimers;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Pages\CreateDisclaimer;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Pages\ViewDisclaimer;
use App\Filament\Clusters\Articles\Resources\Disclaimers\Pages\EditDisclaimer;
use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\DisclaimerResource\Pages;
use App\Models\Disclaimer;
use App\Policies\DisclaimerPolicy;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use App\Filament\Clusters\Articles\Resources\DisclaimerResource\Schema;
use UnitEnum;

/**
 * The DisclaimerResource class provides a comprehensive Filament resource for managing the Disclaimer model.
 *
 * This resource is a part of the "Articles" cluster, ensuring logical grouping within the navigation.
 * It utilizes a modular approach by delegating its form, table, and infolist schema definitions to dedicated classes, which promotes code reusability and maintainability.
 * The resource also integrates with Filament Shield for role-based permission management and leverages caching for performance optimization of the navigation badge.
 *
 * @package App\Filament\Clusters\Articles\Resources
 */
final class DisclaimerResource extends Resource
{
    use HasActiveIcon;

    /**
     * This static property links the Filament resource to its corresponding database model.
     * By setting this, Filament can automatically handle all data-related operations, including querying records for
     * the table, and creating, viewing, and updating individual records via the form and infolist.
     * It is the fundamental link that connects the user interface to the app's data layer.
     */
    protected static ?string $model = Disclaimer::class;

    protected static string|UnitEnum|null $navigationGroup = "Gegevens";

    /**
     * This property defines the icon that represents the resource in the Filament sidebar navigation.
     * Using a clear, descriptive icon helps users quickly identify and navigate to the correct resource.
     * The value 'heroicon-o-information-circle' specifies the "information circle" icon from the outlined Heroicons set.
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-information-circle';

    /**
     * Clusters are a powerful feature in Filament for organizing related resources into a logical group within the navigation.
     * By assigning this property to `Articles::class`, the `DisclaimerResource` will be nested under the "Articles" navigation group.
     * This is highly recommended for larger applications to prevent a cluttered sidebar and improve overall user experience.
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * Configures the form used for creating and updating Disclaimer records.
     *
     * This method delegates the entire form schema definition to the `Schema\FormSchema` class to maintain separation of concerns.
     * This modular design allows the form's fields and validation rules to be managed in a single, dedicated location, which is beneficial for
     * complex forms or when schemas are shared across different parts of the app.
     *
     * @param \Filament\Schemas\Schema $schema The Filament form instance to be configured.
     * @return \Filament\Schemas\Schema The configured Filament form.
     */
    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return FormSchema::configure($schema);
    }

    /**
     * Configures the infolist for the resource's view page.
     *
     * The infolist provides a read-only view of a single Disclaimer record.
     * Like the form, the schema for the infolist's fields is managed externally by `Schema\InfolistSchema`, which is configured here.
     * This ensures consistency between the data displayed and the data collected via the form.
     *
     * @param \Filament\Schemas\Schema $schema The Filament infolist instance to be configured.
     * @return \Filament\Schemas\Schema The configured Filament infolist.
     */
    public static function infolist(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return InfolistSchema::configure($schema);
    }

    /**
     * Configures the table used for listing and filtering Disclaimer records.
     *
     * The table definition, including columns, filters, and actions, is delegated to the `Schema\TableSchema` class.
     * This approach provides a clean, centralized location for managing the resource's list view, making it easy to add or modify columns and search capability.
     *
     * @param  Table $table	 The Filament table instance to be configured
     * @return Table		 The configured Filament table
     */
    public static function table(Table $table): Table
    {
        return TableSchema::configure($table);
    }

    /**
     * Defines the resource's available pages and their corresponding routes.
     *
     * This method links each logical page (e.g., list, create, view, edit) to a specific page class within the `Pages` namespace.
     * This architecture allows for custom logic to be applied to each specific page, such as unique form behaviours or custom actions.
     *
     * @return array<string, PageRegistration> An array of page routes mapped to their page classes.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListDisclaimers::route('/'),
            'create' => CreateDisclaimer::route('/create'),
            'view' => ViewDisclaimer::route('/{record}'),
            'edit' => EditDisclaimer::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            ArticlesRelationManager::class,
        ];
    }

    /**
     * Retrieves the navigation badge content, which displays the total count of Disclaimers registered in the app.
     *
     * To prevent performance degradation from frequent database queries, the count is cached using `Cache::flexible` helper.
     * This ensures that the count is only re-calculated at a maximum interval, reducing the load on the database.
     *
     * @return string|null The formatted count of records, or null if no badge should be displayed.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('disclaimer_count', [10, 60], fn (): string => (string) self::$model::count());
    }
}
