<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers\AuditsRelationManager;
use App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers\EtymologyRelationManager;
use App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers\ReportsRelationManager;
use App\Filament\Clusters\Articles\Resources\ArticleResource\Widgets\ArticleRegistrationChart;
use App\Filament\Exports\ArticleExporter;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\Articles\Pages\CreateWord;
use App\Filament\Resources\Articles\Pages\EditWord;
use App\Filament\Resources\Articles\Pages\ListWords;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Filament\Resources\Articles\RelationManagers\LabelsRelationManager;
use App\Filament\Resources\Articles\RelationManagers\NotesRelationManager;
use App\Filament\Resources\Articles\Schema\FormSchema;
use App\Filament\Resources\Articles\Schema\WordInfolist;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\Article;
use App\UserTypes;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Clusters\Cluster;
use Filament\Resources\Pages\PageRegistration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Cache;
use BackedEnum;

/**
 * Class ArticleResource
 *
 * Resource class for managing dictionary articles within the Filament admin panel.
 * This includes viewing, editing; creating and deleting articles.
 *
 * The resource defines the forms, tables, an relationships necessary for displayinh articles in a structured way.
 * The form includes section for general infoirmation and regional status, whilde the table provides an overview of
 * all articles with search and sorting functionalities.
 *
 * Labels can be linked to articles through the relation manager, and the navigation badge dynamically displays
 * the number of available articles using caching.
 */
final class ArticleResource extends Resource
{
    use HasActiveIcon;

    /**
     * The Eloquent model that this resource represents.
     */
    protected static ?string $model = Article::class;

    /**
     * The navigation icon used in the admin panel menu.
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-language';

    /**
     * The singular label for the model.
     */
    protected static ?string $modelLabel = 'Artikel';

    /**
     * The plural model label for the model.
     */
    protected static ?string $pluralModelLabel = 'Artikelen';

    /**
     * The cluster used for grouping related resources.
     *
     * @todo Check if we can use inheritDoc here
     *
     * @var class-string<Cluster>|null
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * Configures the infolist used to display article details.
     *
     * @param  \Filament\Schemas\Schema  $schema  The Filament infolist instance.
     * @return \Filament\Schemas\Schema The configured infolist.
     */
    public static function infolist(Schema $schema): Schema
    {
        return WordInfolist::make($schema);
    }

    /**
     * Returns an array of relation manager classes that define related resources.
     *
     * @return array<int, class-string> The relation manager classes.
     */
    public static function getRelations(): array
    {
        return [
            LabelsRelationManager::class,
            NotesRelationManager::class,
            ReportsRelationManager::class,
            AuditsRelationManager::class,
            EtymologyRelationManager::class,
        ];
    }

    /**
     * Retrieves the widgets associated with the resource.
     *
     * This method returns an array of Filament widgets that should be displayed on the resource's pages. In this case,
     * it returns the `ArticleRegistrationChart` widget, which displays a chart of article registrations.
     *
     * @return array<int, class-string> An array of widget class names.
     */
    public static function getWidgets(): array
    {
        return [
            ArticleRegistrationChart::class,
        ];
    }

    /**
     * Defines the form used for creating and editing articles.
     * The form consists of sections for general information and regional status,
     * each configured with an icon, description, and specific field schema.
     *
     * @param  \Filament\Schemas\Schema  $schema  The Filament form instance.
     * @return \Filament\Schemas\Schema The configured form.
     */
    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FormSchema::sectionConfiguration('Algemene informatie')
                ->collapsible()
                ->collapsed()
                ->icon('heroicon-o-language')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('De basis informatie omtrent het lemma in het woordenboek')
                ->schema(FormSchema::getDetailSchema()),

            FormSchema::sectionConfiguration('Regio en status van het lemma')
                ->collapsible()
                ->collapsed()
                ->icon('heroicon-o-map')
                ->iconColor('primary')
                ->iconSize(IconSize::Medium)
                ->description('Gegevens omtrent de regio en status van het lemma gebruik')
                ->schema(FormSchema::getStatusAndRegionDetails()),
        ]);
    }

    /**
     * Defines the table configuration for displaying a lost of articles.
     * The table includes columns for author, article (lemma), description, creation date and last updated date.
     * It also configures invidual and builk actions for managing articles.
     *
     * @param  Table  $table  The Filament table instance.
     * @return Table The configured table.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Action::make('docs')
                    ->label('Help')
                    ->icon('heroicon-o-lifebuoy')
                    ->color('gray')
                    ->url('https://vl-wbk.github.io/documentatie-portaal/artikelen/')
                    ->openUrlInNewTab(),
                CreateAction::make()
                    ->icon('heroicon-o-document-plus'),
            ])
            ->heading('Woordenboek artikelen')
            ->description('Een overzicht van alle artikelen die geregistreerd staan In het Vlaams Woordenboek gebruik de filters om de woorden te verkrijgen per status.')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen artikelen gevonden')
            ->emptyStateDescription("Momenteel konden we geen artikelen (lemma's) vinden met de matchende criteria. Kom later nog eens terug.")
            ->paginated([10, 25, 50, 75])
            ->columns([
                TextColumn::make('author.name')
                    ->label('Ingevoegd door')
                    ->searchable()
                    ->placeholder('onbekende gebruiker')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->toggleable(),
                TextColumn::make('state')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('word')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->color('primary')
                    ->label('Lemma'),
                TextColumn::make('partOfSpeech.name')
                    ->label('woordsoort')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('characteristics')
                    ->label('kenmerken')
                    ->placeholder('-')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Toegevoegd op')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Laast gewijzigd')
                    ->sortable()
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                ViewAction::make()->hiddenLabel(),
                EditAction::make()->hiddenLabel(),
                RestoreAction::make()->hiddenLabel()->color('danger'),
                DeleteAction::make()->hiddenLabel(),
            ])
            ->filters([
                SelectFilter::make('state')
                    ->label('status')
                    ->multiple()
                    ->options(ArticleStates::class),
                TrashedFilter::make()
                    ->native(false)
                    ->visible(fn (): bool => auth()->user()->canAny('restore', Article::class)),
                Filter::make('assigned')
                    ->label('Toegewezen aan mij')
                    ->query(fn (Builder $query): Builder => $query->where('editor_id', auth()->id())),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),

                    ExportBulkAction::make()->exporter(ArticleExporter::class)
                        ->modalWidth(Width::Large)
                        ->modalDescription('Gegevens nodig in een ander programma? Geen probleem! Selecteer de kolommen die je nodig hebt en je kunt vervolgens de gegevens downloaden in een .xlsx of .csv estanden downloaden')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->slideOver(),
                ]),
            ]);
    }

    /**
     * Modifies the Eloquent query to exclude soft deleted articles for non-editor users.
     *
     * This method overrides the default Eloquent query to remove the global scope that automatically excludes soft-deleted records.
     * This allows administrators and other privileged users to see soft-deleted articles in the list.
     * Editor and EditorInChief users will see all records.
     *
     * @return Builder<Article> The modified Eloquent query builder.
     */
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->user_type->in(enums: [UserTypes::Editor, UserTypes::EditorInChief])) {
            return parent::getEloquentQuery();
        }

        return parent::getEloquentQuery()
            ->withoutGlobalScopes([SoftDeletingScope::class]);
    }

    /**
     * Determines what text should be shown as the main title in global search results.
     * In this case, we display the word (lemma) itself as the primary identifier.
     *
     * For example: If searching for "duusterzot", the result will show "duusterzot" as the title.
     *
     * @param  Article  $record  The article record being displayed in search results
     * @return string The word/lemma to display as the search result title
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return "#$record->id " . $record->word;
    }

    /**
     * Specifies which database columns should be included in the global search.
     *
     * This makes articles findable by:
     * - their word/lemma
     * - their ID number
     * - any keywords associated with them
     *
     * @return array<string> List of searchable column names
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['word', 'id', 'keywords'];
    }

    /**
     * Defines what additional information should appear below the title in global search results.
     *
     * For each article, we show:
     * - The word's unique ID number (for reference)
     * - Any characteristics/properties of the word
     *
     * This helps users quickly identify if they've found the right word entry.
     *
     * @param  Article  $record  The article record being displayed
     * @return array<string, mixed> Key-value pairs of labels and their values
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            $record->characteristics,
        ];
    }

    /**
     * Retrieves the navigation badge count for the articles.
     * This count is cached to reduce database queries and improve performance.
     *
     * @return string|null THe navigation badge displaying the numbver or articles.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('lemma_count', [10, 60], fn (): string => (string) self::$model::count());
    }

    /**
     * Defines the routes for the resource's pages.
     * The pages include listing, creating, viewing, and editing articles.
     *
     * @return array<string, PageRegistration> An associative array mapping page keys to their routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => ListWords::route('/'),
            'create' => CreateWord::route('/create'),
            'view' => ViewWord::route('/{record}'),
            'edit' => EditWord::route('/{record}/edit'),
        ];
    }
}
