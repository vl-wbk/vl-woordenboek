<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages\CreateWordOfTheDay;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages\EditWordOfTheDay;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages\ListWordOfTheDays;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas\WordOfTheDayForm;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas\WordOfTheDaysInfolist;
use App\Filament\Clusters\Articles\Resources\WordOfTheDays\Tables\WordOfTheDaysTable;
use App\Filament\Support\Concerns\HasActiveIcon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use App\Models\WordOfTheDay;
use UnitEnum;

/**
 * WordOfTheDayResource - the editorial heart of our "Woord van de dag" feature.
 *
 * This resource acts as the bridge between our database and the admin panel, allowing editors to curate and schedule linguistic highlights for our users. 
 * By managing these entries, we ensure that every day brings a fresh piece of Flemish heritage to the spotlight.
 * Whether it's a rare dialect term or a common expression with a surprising history, this is where the scheduling and presentation logic for those daily highlights live.
 *
 * @package App\Filament\Clusters\Articles\Resources\WordOfTheDays
 */
final class WordOfTheDayResource extends Resource
{
    use HasActiveIcon;

    /**
     * The underlying Eloquent model that this resource orchestrates.
     * This tells Filament which data structure we are interacting with in the database.
     * 
     *  @var class-string<WordOfTheDay>|null
     */
    protected static ?string $model = WordOfTheDay::class;

    /**
     * The visual identifier for this resource in the navigation menu.
     * We use the calendar icon to represent the temporal nature of daily word scheduling.
     *
     * @var string|BackedEnum|null
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    /**
     * The cluster that organizes this resource within the broader application.
     * This groups related administrative tasks together for a cleaner user experience.
     *
     *  @var class-string|null
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * The singular human-readable label for the resource.
     * This is what editors will see when referring to a single entry in the panel.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Woord van de dag';

    /**
     * The plural human-readable label for the resource.
     * Used in navigation menus and headers to describe the collection of daily words.
     * 
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Woorden van de dag';

    /**
     * The navigation group under which this resource is categorized.
     * This helps logically separate core content from supporting administrative tools.
     *
     * @var UnitEnum|string|null
     */
    protected static UnitEnum|string|null $navigationGroup = 'Ondersteuning';

    /**
     * The attribute used to represent the record in global search and breadcrumbs. 
     * We use the formatted date to make it easy for editors to identify specific entries.
     *
     * @var string|null
     */
    protected static ?string $recordTitleAttribute = 'formatted_scheduled_for';

    /**
     * Configures the data entry form for our daily words.
     * This defines the fields and layout editors use when creating or updating an entry, ensuring that data is captured consistently and correctly.
     *
     * @param Schema $schema The schema instance to be configured.
     * @return Schema The configured form schema.
     */
    public static function form(Schema $schema): Schema
    {
        return WordOfTheDayForm::configure($schema);
    }

    /**
     * Configures the read-only information list.
     * This provides a clear, structured view of a word's details without the distraction of input fields, perfect for reviewing content.
     *
     * @param  Schema $schema  The schema instance to be configured.
     * @return Schema          The configured infolist schema.
     */
    public static function infolist(Schema $schema): Schema
    {
        return WordOfTheDaysInfolist::configure($schema);
    }

    /**
     * Defines the overview table structure. This setup determines how the queue of words is displayed, filtered,
     * and searched in the list view for efficient management.
     *
     * @param  Table $table  The table instance to be configured.
     * @return Table         The configured management table.
     */ 
    public static function table(Table $table): Table
    {
        return WordOfTheDaysTable::configure($table);
    }

    /**
     * Registers the routes for this resource. 
     * These pages repreent the different 'views' available in the admin panel: 
     * 
     * - index: The Bird's-eye view oàf all scheduled words. 
     * - create: The entry point for adding a new word to the queue. 
     * - edit: The interface for refining existing scheduled entries.
     *
     * @return array
     */
    public static function getPages(): array
    {
        return [
            'index' => ListWordOfTheDays::route('/'),
            'create' => CreateWordOfTheDay::route('/create'),
            'edit' => EditWordOfTheDay::route('/{record}/edit'),
        ];
    }
}
