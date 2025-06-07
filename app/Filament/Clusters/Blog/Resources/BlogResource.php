<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources;

use App\Filament\Clusters\Blog;
use App\Filament\Clusters\Blog\Resources\BlogResource\Pages;
use App\Filament\Clusters\Blog\Resources\BlogResource\Schema\BlogPostInfolist;
use App\Filament\Clusters\Blog\Resources\BlogResource\Schema\FormSchema;
use App\Filament\Clusters\Blog\Resources\BlogResource\Schema\ResourceActionDefinitions;
use App\Filament\Clusters\Blog\Resources\BlogResource\Schema\TableSchema;
use App\Models\Blog as BlogPosts;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;

/**
 * Class BlogResource
 *
 * This class defines the Filament administrative interface for managing 'BlogPosts' (articles).
 * It acts as a central hub for configuring how blog posts are displayed, created, edited, and deleted within the Filament admin panel.
 *
 * The resource leverages separate schema classes (`FormSchema`, `TableSchema`, `ResourceActionDefinitions`) to maintain a clean separation of concerns and improve code organization.
 * This approach makes the definition of forms, tables, and actions highly modular and reusable.
 *
 * Key features defined by this resource include:
 *
 * - Association with the `App\Models\Blog` Eloquent model (aliased as `BlogPosts`).
 * - Navigation icon, label, and cluster placement within the Filament sidebar.
 * - Detailed configuration for the article listing table, including custom headings, descriptions, and empty states.
 * - Definition of routes for listing, creating, and editing blog posts.
 *
 * @package App\Filament\Clusters\Blog\Resources
 */
final class BlogResource extends Resource
{
    /**
     * The model associated with this resource.
     * This tells Filament which Eloquent model this resource will manage.
     */
    protected static ?string $model = BlogPosts::class;

    /**
     * The icon displayed next to the navigation item for this resource.
     * Uses a Heroicons outline icon.
     */
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    /**
     * The label displayed in the Filament navigation sidebar for this resource.
     * Set to 'Artikelen' (Articles).
     */
    protected static ?string $navigationLabel = 'Artikels';

    /**
     * The cluster this resource belongs to.
     * This organizes resources into logical groups within the Filament sidebar.
     */
    protected static ?string $cluster = Blog::class;

    /**
     * The singular, human-readable label for the Eloquent model managed by this resource.
     * This label is used throughout the Filament UI when referring to a single instance of the model, such as in form headings, action buttons, and confirmation messages.
     *
     * For example, if your model is 'Post', setting this to 'Article' would result in UI elements like "Create new Article" or "Edit Article".
     */
    protected static ?string $modelLabel = 'Artikel';

    /**
     * Defines the structure of the form used for creating and editing blog posts.
     * It delegates the actual form component definition to the `FormSchema` class.
     *
     * @param  Form $form  The Filament Form instance.
     * @return Form        The configured Filament Form instance.
     */
    public static function form(Form $form): Form
    {
        return FormSchema::getComponents($form);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BlogPostInfolist::getComponent($infolist);
    }

    /**
     * Defines the structure and behavior of the table used to list blog posts.
     * It configures labels, descriptions, empty states, and delegates column and action definitions to `TableSchema` and `ResourceActionDefinitions`.
     *
     * @param  Table $table  The Filament Table instance.
     * @return Table         The configured Filament Table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->modelLabel('Artikel')
            ->pluralModelLabel('Artikelen')
            ->heading('Artikelen overzicht')
            ->description('Het Vlaams woordenboek is een levend wezen. Dat volop groeit en evolueerd naar de behoeftes en noden van gebruikers. Maar soms is het intressant om inzichten te geven in taalkundige kwesties en of de evolutie van het Woordenboek. Daarom kunt hier artikelen aanmaken en publiceren om gebruikers op de hoogte te houden.')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen artikelen gevonden of aangemaakt')
            ->emptyStateDescription('Het lijkt erop dat er momenteel nog geen artikelen zijn aangemaakt of gevonden met opgegeven criteria. Maak een artikel aan of kom later nog eens terug.')
            ->columns(components: TableSchema::getColumnComponents())
            ->headerActions(actions: ResourceActionDefinitions::getHeaderActions())
            ->actions(actions: ResourceActionDefinitions::getTableActions())
            ->bulkActions(actions: ResourceActionDefinitions::getBulkActions());
    }

    /**
     * Defines the routes and socciated page classes for this resource.
     * This maps URLs to specific Filament pages (list, create, view, edit)
     *
     * @return array  An array of page route definitions
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'view' => Pages\ViewBlog::route('/{record}'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
