<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\Categories;

use App\Filament\Support\Concerns\HasActiveIcon;
use Filament\Schemas\Schema;
use App\Filament\Clusters\Blog\Resources\Categories\Pages\ListCategories;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Clusters\Blog\BlogCluster;
use App\Filament\Clusters\Blog\Resources\CategoryResource\Pages;
use App\Filament\Clusters\Blog\Resources\Categories\Schema\CategoryInformationList;
use App\Filament\Clusters\Blog\Resources\Categories\Schema\FormSchema;
use App\Filament\Clusters\Blog\Resources\Categories\Schema\TableActionsDefinitions;
use App\Filament\Clusters\Blog\Resources\Categories\Schema\TableColumnSchema;
use App\Models\Category;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Filament Resource for managing news categories within the Blog cluster.
 *
 * This resource provides a user interface for administrators to:
 * - Create and edit news categories via a structured form
 * - Browse a tabular overview of all existing categories
 * - View detailed, read-only information for individual categories
 *
 * It is grouped under the Blog cluster in the Filament navigation for improved organization.
 * All form, table, and infolist configurations are delegated to dedicated schema classes to promote separation of concerns and maintainability.
 *
 * @package App\Filament\Clusters\Blog\Resources
 */
final class CategoryResource extends Resource
{
    use HasActiveIcon;

    /**
     * The Eloquent model that this resource is tied to.
     * This model will be used by Filament to retrieve, update, and delete category data in the database.
     */
    protected static ?string $model = Category::class;

    /**
     * The icon that appears in the Filament navigation sidebar for this resource.
     * Use Heroicons icon names, prefixed with `heroicon-o-` or `heroicon-s-`.
     */
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-tag';

    /**
     * This links the resource to the Blog cluster in Filament.
     * It helps organize navigation by grouping categories under the Blog section in the admin panel.
     *
     * {@inheritdoc}
     */
    protected static ?string $cluster = BlogCluster::class;

    /**
     * The singular, human-readable label used throughout the Filament UI for this model.
     * Used in places like buttons, page titles, and breadcrumbs.
     */
    protected static ?string $modelLabel = 'Categorie';

    /**
     * The plural, human-readable label used throughout the Filament UI for this model.
     * Used in areas like headings and resource overviews.
     */
    protected static ?string $pluralModelLabel = 'Categorieen';

    /**
     * This method builds the form for creating and editing categories.
     * It delegates the entire form structure to 'FormSchema::getDefinition($form)'.
     * This keeps the form's complexity out of this resource class, making it more manageable.
     *
     * @param \Filament\Schemas\Schema $schema The Filament Form instance.
     * @return \Filament\Schemas\Schema The configured Filament Form instance.
     */
    public static function form(Schema $schema): Schema
    {
        return FormSchema::getDefinition($schema);
    }

    /**
     * This configures the infolist for displaying detailed category information.
     * Similar to the form, the layout is defined externally by `CategoryInformationList::getInfolist($infolist)`.
     * This ensures a consistent and reusable way to show category details
     *
     * @param \Filament\Schemas\Schema $schema The Filament infolist instance.
     * @return \Filament\Schemas\Schema The configured Filament Infolist Instance.
     */
    public static function infolist(Schema $schema): Schema
    {
        return CategoryInformationList::getInfolist($schema);
    }

    /**
     * Defines the table schema used to display a list of categories in a tabular format.
     *
     * Includes:
     *  - A heading and description for context
     *  - Empty state UI when no data is found
     *  - Column definitions for what should be displayed
     *  - Row actions (edit, delete, view, etc.)
     *  - Header actions (create new, import/export, etc.)
     *  - Bulk actions (delete multiple, export, etc.)
     *
     * The table schema is delegated to `TableColumnSchema` and `TableActionsDefinitions`
     * to centralize logic and maintain consistency across related resources.
     *
     * @param  Table $table  The Filament Table instance.
     * @return Table         The configured Filament Table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->heading(heading: __('category-resource.table.heading'))
            ->description(description: __('category-resource.table.description'))
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading(heading: __('category-resource.table.empty-state.heading'))
            ->emptyStateDescription(description: __('category-resource.table.empty-state.description'))
            ->columns(components: TableColumnSchema::getComponents())
            ->recordActions(actions: TableActionsDefinitions::getRowActions())
            ->toolbarActions(actions: TableActionsDefinitions::getBulkActions());
    }

    /**
     * Returns the route definitions for the Filament pages associated with this resource.
     *
     * Filament uses this method to map page identifiers (e.g., 'index', 'create', 'edit') to their corresponding route handlers. This controls how pages are accessed from the UI.
     * Currently, only the index page is defined, which displays a list of all categories.
     * Additional pages (like creation, edit, or view) can be added here as needed.
     *
     * @return array<string, PageRegistration> An associative array where keys are page identifiers and values are the fully qualified page class names.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
        ];
    }
}
