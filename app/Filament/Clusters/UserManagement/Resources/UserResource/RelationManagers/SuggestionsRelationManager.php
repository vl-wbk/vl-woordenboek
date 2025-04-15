<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\UserResource\RelationManagers;

use App\Models\User;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Models\Article;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Manages the user's suggestions relation.
 *
 * This RelationManager displays suggestions on user detail pages (ViewUser) using a custom table layout.
 * It defines the relationship key, table headings, empty state messages, column layouts, and row actions.
 *
 * @package App\Filament\Clusters\UserManagement\Resources\UserResource\RelationManagers;
 */
final class SuggestionsRelationManager extends RelationManager
{
    /**
     * The relationship key that connects a User to their suggestions.
     *
     * This static variable tells Filament which Eloquent relationship to use when loading suggestion data for a user.
     * The value 'suggestions' corresponds to the name of the relationship method defined on the User model.
     */
    protected static string $relationship = 'suggestions';

    /**
     * The title to be displayed for this suggestions relation in the UI.
     * This helps users quickly identify the section dedicated to suggestions.
     */
    protected static ?string $title = 'Suggesties';

    /**
     * The badge color used to display the number of suggestions.
     * A gray badge indicates a neutral or standard state.
     */
    protected static ?string $badgeColor = 'gray';

    /**
     * The icon representing this suggestions relation in the user interface.
     *
     * This icon is displayed alongside the relation title in Filament's UI to visually denote the suggestions section.
     * It uses the 'heroicon-o-document-text' icon, which is part of the Heroicons set, to maintain a consistent look and feel.
     */
    protected static ?string $icon = 'heroicon-o-document-text';

    /**
     * Determines whether the suggestions relation view is allowed for a given record.
     * Access is restricted to pages that are an instance of ViewUser.
     * This ensures that the suggestions are only displayed in the context of a user detail view.
     *
     * @param  Model  $ownerRecord  The parent record associated with this relation.
     * @param  string $pageClass    The fully qualified class name of the current page.
     * @return bool                 Returns true if the current page is an instance of ViewUser, false otherwise.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass instanceof ViewUser;
    }

    /**
     * Returns a badge string representing the count of related suggestions.
     *
     * If the owner record has one or more suggestions (i.e. the query count is greater than zero),
     * this method will return the count cast to a string. If there are no suggestions, it returns null.
     *
     * @param  User   $ownerRecord  The record that owns the suggestions.
     * @param  string $pageClass    The current page's class (unused in this method).
     * @return string|null          The count of suggestions as a string, or null if there are none.
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $recordCount = $ownerRecord->suggestions()->count();

        if ($recordCount > 0) {
            return (string) $recordCount;
        }

        return null;
    }

    /**
     * Configures the table that displays the suggestions.
     *
     * The table is set with a heading, a detailed description, and empty state messages to guide the user.
     * It also specifies the labels for individual suggestion records in both singular and plural form.
     * In addition, the table layout is customized by invoking helper methods that return the column schema layout and the action definitions.
     *
     * @param  Table  $table  The table instance to be configured.
     * @return Table          Returns the table instance with the desired configuration for displaying suggestions.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Ingezonden Suggesties')
            ->description('Hier zie je de voorstellen die de gebruiker heeft ingediend voor nieuwe woorden of aanvullingen in het woordenboek. Deze suggesties bieden inzicht in hoe actief een gebruiker bijdraagt en welke thema’s of termen voor hen relevant zijn. Gebruik deze informatie om gerichte opvolging te doen of om kansen te signaleren voor verdere samenwerking of feedback.')
            ->emptyStateHeading('Geen suggesties gevonden')
            ->emptyStateDescription('Deze gebruiker heeft nog geen suggesties ingestuurd. Zodra er voorstellen worden ingediend, verschijnen ze hier.')
            ->modelLabel('Suggestie')
            ->pluralModelLabel('Suggesties')
            ->columns($this->getTableColumnSchemaLayout())
            ->actions($this->getTableActionDefinitions());
    }

    /**
     * Returns an array of column configurations for the suggestions table. Each column is built using Filament's
     * TextColumn class and is configured to display specific fields such as the unique identifier, the state of the suggestion, the lemma, the part of speech, any characteristics, and the date the suggestion was submitted.
     * The columns support sorting, searching, and visual enhancements such as badges and color styling.
     *
     * @return array<\Filament\Tables\Columns\Column|\Filament\Tables\Columns\ColumnGroup|\Filament\Tables\Columns\Layout\Component> An array of TextColumn instances defining the layout of the suggestions table.
     */
    public function getTableColumnSchemaLayout(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->label('#')
                ->weight(FontWeight::Bold)
                ->sortable()
                ->color('primary'),
            Tables\Columns\TextColumn::make('state')
                ->label('Artikelstatus')
                ->sortable()
                ->badge(),
            Tables\Columns\TextColumn::make('word')
                ->label('Lemma')
                ->searchable(),
            Tables\Columns\TextColumn::make('partOfSpeech.name')
                ->label('Woordsoort')
                ->sortable(),
            Tables\Columns\TextColumn::make('characteristics')
                ->label('Kenmerken')
                ->sortable(),
            Tables\Columns\TextColumn::make('created_at')
                ->label('Ingezonden op')
                ->sortable()
                ->date(),
        ];
    }

    /**
     * Returns an array of actions that are available on each row of the suggestions table.
     * The action defined here allows the user to view more detailed information about a suggestion.
     * It constructs a URL to the suggestion detail view provided by the ArticleResource, ensuring that users can easily access extended information about a particular suggestion.
     *
     * @return array<mixed>  An array of action definitions that will be attached to each row in the table.
     */
    public function getTableActionDefinitions(): array
    {
        return [
            Tables\Actions\ViewAction::make()
                ->url(fn (Article $article): string => ArticleResource::getUrl('view', ['record' => $article]))
        ];
    }
}
