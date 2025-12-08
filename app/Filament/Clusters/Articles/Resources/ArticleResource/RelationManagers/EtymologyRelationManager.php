<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use App\Filament\Clusters\Articles\Resources\Etymologies\Schema\FormSchema;
use Filament\Support\Enums\Width;
use App\Models\Article;
use App\Filament\Clusters\Articles\Resources\Etymologies\Schema\TableSchema;
use App\Filament\Resources\Articles\Pages\ViewWord;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Manages the Etymology relationship for Article resources in the Filament admin panel.
 *
 * This relation manager is responsible for displaying, filtering, and managing the etymology records associated with a given article.
 * Etymology entries provide historical and linguistic background for words, including their origins, language of borrowing, historical forms, and semantic evolution.
 * The manager defines how etymology data is presented in both table and form views, and customizes the user experience with descriptive headings, icons, and empty state messages.
 * It also restricts visibility to the appropriate context (the ViewWord page).
 * Developers can extend or override the configuration by modifying the associated Schema and TableSchema classes, which encapsulate the form and table logic for etymology records.
 *
 * @property Article $ownerRecord
 *
 * @package App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers
 */
final class EtymologyRelationManager extends RelationManager
{
    /**
     * The name of the relationship as defined on the Article model.
     *
     * This string must match the relationship method name on the Article Eloquent model.
     * It tells Filament which related records to manage in this section.
     * In this case, 'etymology' should correspond to a hasMany or morphMany relationship on the Article model, returning all etymology records for a given article.
     */
    protected static string $relationship = 'etymologies';

    /**
     * The icon used throughout the Filament UI for this relation manager.
     *
     * This icon is displayed in the sidebar, headers, and empty states to visually represent the etymology section.
     * The value should be a valid Heroicon identifier.
     * Using a clock icon ('heroicon-o-clock') here symbolizes the historical and time-related nature of etymological information.
     */
    protected static string | \BackedEnum | null $icon = 'heroicon-o-clock';

    /**
     * The display title for the etymology relation manager section.
     *
     * This title appears in the Filament admin panel as the heading for the section managing etymology records.
     * It should be concise and clearly indicate the purpose of the section to users. In this case, 'Etymologie' is the Dutch word for 'Etymology', aligning with the application's language and terminology.
     */
    protected static ?string $title = 'Etymologie';

    /**
     * Indicates whether the relation manager is read-only.
     *
     * @return bool Always returns false, allowing full CRUD operations.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Determines if the relation manager should be visible for a given record and page.
     * Only shows the etymology relation manager on the ViewWord page.
     *
     * @param  Model    $ownerRecord  The parent Article model instance.
     * @param  string   $pageClass    The current Filament page class.
     * @return bool                   True if the relation manager should be shown, false otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    /**
     * Configures the form used to create or edit etymology records.
     * Delegates the form schema configuration to the EtymologyResource\Schema\FormSchema class, allowing for centralized and reusable form definitions.
     *
     * @param \Filament\Schemas\Schema $schema The Filament form instance.
     * @return \Filament\Schemas\Schema The configured form instance.
     */
    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return FormSchema::configure($schema);
    }

    /**
     * Configures the table view for displaying etymology records.
     *
     * Sets up the table's description, columns, filters, actions, and empty state messages.
     * Uses the TableSchema class for column, filter, and action definitions, ensuring consistency and maintainability across the application.
     *
     * @param  Table $table The Filament table instance.
     * @return Table        The configured table instance.
     *
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->description(description: 'De etymologie beschrijft de herkomst en geschiedenis van een woord. In deze sectie ontdek je hoe een woord is ontstaan, uit welke taal het is overgenomen, en hoe het zich in de loop van de tijd heeft ontwikkeld. We verwijzen daarbij naar verwante vormen in andere talen, historische spellingswijzen en oorspronkelijke betekenissen. Zo krijg je inzicht in de wortels van het woord en de weg die het heeft afgelegd naar het huidige gebruik in het Nederlands.')
            ->emptyStateIcon(icon: self::$icon)
            ->emptyStateHeading(heading: 'Geen gegevens gevonden')
            ->emptyStateDescription(description: 'Er zijn geen gegevens gevonden voor de etymologie van het woord')
            ->columns(components: TableSchema::configureColumns())
            ->filters(filters: TableSchema::configureFilters())
            ->filtersFormWidth(width: Width::Medium)
            ->recordActions(actions: TableSchema::configureActions())
            ->toolbarActions(actions: TableSchema::configureBulkActions())
            ->headerActions(actions: TableSchema::configureHeaderActions($this->ownerRecord));
    }
}
