<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Enums\ArticleStates;
use App\Filament\Clusters\Articles;
use App\Filament\Resources\ArticleResource\Schema\WordInfolist;
use App\Filament\Resources\ArticleResource\Pages;
use App\Filament\Resources\ArticleResource\Schema\FormSchema;
use App\Models\Article;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

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
 *
 * @package App\Filament\Resources
 */
final class ArticleResource extends Resource
{
    /**
     * The Eloquent model that this resource represents.
     *
     * @var string|null
     */
    protected static ?string $model = Article::class;

    /**
     * The navigation icon used in the admin panel menu.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-language';

    /**
     * The singular label for the model.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Artikel';

    /**
     * The plural model label for the model.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = "Artikelen";

    /**
     * The cluster used for grouping related resources.
     *
     * @var class-string<\Filament\Clusters\Cluster>|null
     */
    protected static ?string $cluster = Articles::class;

    /**
     * Configures the infolist used to display article details.
     *
     * @param  Infolist  $infolist  The Filament infolist instance.
     * @return Infolist             The configured infolist.
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return WordInfolist::make($infolist);
    }

    /**
     * Returns an array of relation manager classes that define related resources.
     *
     * @return array<int, class-string>  The relation manager classes.
     */
    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\ArticleResource\RelationManagers\LabelsRelationManager::class,
            \App\Filament\Resources\ArticleResource\RelationManagers\NotesRelationManager::class,
        ];
    }

    /**
     * Defines the form used for creating and editing articles.
     * The form consists of sections for general information and regional status,
     * each configured with an icon, description, and specific field schema.
     *
     * @param  Form $form  The Filament form instance.
     * @return Form        The configured form.
     */
    public static function form(Form $form): Form
    {
        return $form->schema([
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
     * @param  Table $table  The Filament table instance.
     * @return Table         The configured table.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen artikelen gevonden')
            ->emptyStateDescription("Momenteel konden we geen artikelen (lemma's) vinden met de matchende criteria. Kom later nog eens terug.")
            ->paginated([10, 25, 50, 75])
            ->modifyQueryUsing(fn (Builder $query): Builder => self::selectDatabaseColumns($query))
            ->columns([
                TextColumn::make('author.name')
                    ->label('Ingevoegd door')
                    ->searchable()
                    ->placeholder('onbekend')
                    ->icon('heroicon-o-user-circle')
                    ->iconColor('primary')
                    ->toggleable(),
                TextColumn::make('word')
                    ->searchable()
                    ->weight(FontWeight::SemiBold)
                    ->color('primary')
                    ->label('Lemma'),
                TextColumn::make('partOfSpeech.name')
                    ->label('woordsoort')
                    ->sortable(),
                TextColumn::make('characteristics')
                    ->label('kenmerken')
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
            ->actions([
                Tables\Actions\ViewAction::make()->hiddenLabel(),
                Tables\Actions\EditAction::make()->hiddenLabel(),
                Tables\Actions\DeleteAction::make()->hiddenLabel(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Selects specific database columns for the article listing table.
     * This optimizes the query by only retrieving necessary fields.
     *
     * @param  Builder<Article> $builder  The query builder instance
     * @return Builder<Article>           The modified query builder
     */
    private static function selectDatabaseColumns(Builder $builder): Builder
    {
        return $builder->addSelect('id', 'characteristics', 'part_of_speech_id', 'word', 'state', 'author_id', 'created_at', 'updated_at');
    }

    /**
     * Determines what text should be shown as the main title in global search results.
     * In this case, we display the word (lemma) itself as the primary identifier.
     *
     * For example: If searching for "duusterzot", the result will show "duusterzot" as the title.
     *
     * @param   Article $record    The article record being displayed in search results
     * @return  string             The word/lemma to display as the search result title
     */
    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->word;
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
     * @param  Article $record       The article record being displayed
     * @return array<string, mixed>  Key-value pairs of labels and their values
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'woord ID' => $record->id,
            'kenmerken' => $record->characteristics,
        ];
    }

    /**
     * Retrieves the navigation badge count for the articles.
     * This count is cached to reduce database queries and improve performance.
     *
     * @return string|null  THe navigation badge displaying the numbver or articles.
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('lemma_count', [10, 60], function (): string {
            return (string) self::$model::count();
        });
    }

    /**
     * Defines the routes for the resource's pages.
     * The pages include listing, creating, viewing, and editing articles.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration>  An associative array mapping page keys to their routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWords::route('/'),
            'create' => Pages\CreateWord::route('/create'),
            'view' => Pages\ViewWord::route('/{record}'),
            'edit' => Pages\EditWord::route('/{record}/edit'),
        ];
    }
}
