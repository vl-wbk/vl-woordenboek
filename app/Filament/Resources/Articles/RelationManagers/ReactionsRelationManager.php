<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Enums\Articles\InsightCategory;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Models\Reaction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * ReactionsRelationManager
 *
 * This class serves as a bridge between the current Article resource and legacy comment data stored in the 'reactions' table.
 * Its primary purpose is to provide a read-only or classification-focused interface for data imported from the previous Vlaams Woordenboek platform.
 *
 * Design Architecture:
 * - Scope: Specifically limited to the 'View' context to prevent cluttering the Edit forms.
 * - Performance: Implements a aggressive caching strategy for the tab badges to prevent redundant COUNT queries on large datasets.
 * - Interaction: Features an "In-Modal Action" pattern, allowing editors to update metadata (categorization) without navigating away from the full-text view of the reaction.
 *
 * @package App\Filament\Resources\Articles\RelationManagers
 * @property-read \App\Models\Article $ownerRecord The parent Article model instance.
 */
final class ReactionsRelationManager extends RelationManager
{
    /**
     * The Eloquent relationship name.
     * This must correspond to the `reactions()` method defined in the Article model.
     * It handles the retrieval of related Reaction records.
     */
    protected static string $relationship = 'reactions';

    /**
     * The localized title displayed in the header of the Relation Manager.
     * "Community inzichten" is used here to distinguish legacy reactions from  modern system logs or user comments.
     */
    protected static ?string $title = 'Reacties VW 1.0';

    /**
     * The primary theme color for UI accents.
     * This color is applied to the ID column, modal icons, and primary action buttons to maintain visual consistency across the resource.
     */
    private static string $defaultColor = 'primary';

    /**
     * The icon rendered in the relation manager tab.
     * Uses the 'tag' icon to signify that the primary task here is the  categorization/labeling of community feedback.
     */
    protected static \BackedEnum|null|string $icon = 'heroicon-o-tag';

    /**
     * Generates a dynamic badge count for the Relation Manager tab.
     *
     * Technical Implementation:
     * To optimize the performance of the Article resource (especially when multiple tabs  are present), the reaction count is stored in the application cache.
     * The cache key incorporates the route key of the owner record to ensure unique scoping.
     * This cache should be invalidated via a Model Observer on the Reaction model upon creation or deletion.
     *
     * @param  Model   $ownerRecord     The parent Article instance.
     * @param  string  $pageClass       The class name of the current Filament page.
     * @return string|null              The string representation of the count, or null.
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        /** @var \App\Models\Article $entityRecord */
        $entityRecord = $ownerRecord;

        return Cache::rememberForever('community-insights:' . $entityRecord->getRouteKey(), function () use ($entityRecord): string {
            return (string) $entityRecord->reactions()->count();
        });
    }

    /**
     * Conditional rendering logic for the Relation manager.
     *
     * This method implements two giard claues:
     *
     * 1. Context Guard:
     * Ensures the manager only appears on the 'ViewWord' page. This keeps the 'Edit' and 'Create' workflows lean.
     *
     * 2. Data Guard:
     * Checks if there is actually data to display. If no reactions exist for a given article,
     * The tab is completely hidden to improve the UX for "clean" articles.
     *
     * @param  Model   $ownerRecord The parent Article instance.
     * @param  string  $pageClass   The current Filament page FQCN.
     * @return bool
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return new $pageClass() instanceof ViewWord && self::getBadge($ownerRecord, $pageClass) > 0;
    }

    public function isReadOnly(): bool
    {
        return $this->getOwnerRecord()->trashed() ? true : false;
    }

    /**
     * Defines the Table configuration and behavioral settings.
     *
     * The table is configured to provide a high-level overview of legacy data.?
     * It includes custom ezmpty state descriptions to explain to the user why they might see
     * no data and emphasizes the utility of these insights for content refinement.
     *
     * @param  Table $table  The Filament Table builder instance.
     * @return Table         The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Community inzichten')
            ->description('Een overzicht van alle oude reacties die gekoppeld waren aan het artikel in de oude versie van het Vlaams Woordenboek. Deze kunnen nuttig zijn voor verdere verfijning van het artikel in kwestie.')
            ->emptyStateIcon(Heroicon::OutlinedChatBubbleLeftRight)
            ->emptyStateHeading('Geen reacties gevonden')
            ->emptyStateDescription('Het lijkt erop dat in de vorige versie van het Vlaams Woordenboek die artikel geen reacties had of er geen matchen mat de opgegeven criteria.')
            ->columns($this->getTableColumnSchema())
            ->filters($this->getFilters())
            ->recordActions($this->getTableRecordActions());
    }

    /**
     * Configures the data filtering layer for the table.
     *
     * Provides a SelectFilter based on the InsightCategory BackedEnum.
     * The use of native(false) ensures a consistent, searchable UI component regardless of the brower's default select rendering.
     *
     * @return array<int, SelectFilter> List of configured table filters.
     */
    private function getFilters(): array
    {
        return [
            SelectFilter::make('insight_category')
                ->native(false)
                ->options(InsightCategory::class),
        ];
    }

    /**
     * Defines the visual schema for the table colums.
     *
     * Column Logic:
     * - 'id':                Bolded and colored for clear record identification.
     * - 'insight_category':  Rendered as a badge tto provide immediate visual classification. (e.g., Correction, Example, Etymology)
     * - 'title':             Searchable field with a placeholder for records missing legacy headers.
     * - 'created_at':        Cast to a localized date format for chronological context.
     *
     * @return array<int, TextColumn> List of column definitions.
     */
    private function getTableColumnSchema(): array
    {
        return [
            TextColumn::make('id')
                ->weight(FontWeight::Bold)
                ->color(self::$defaultColor)
                ->label('#'),
            TextColumn::make('insight_category')
                ->label('Categorisering')
                ->icon(Heroicon::OutlinedListBullet)
                ->badge(),
            TextColumn::make('author')
                ->label('Ingevoegd door'),
            TextColumn::make('title')
                ->label('Titel')
                ->make('title')
                ->placeholder('- Deze reactie heeft geen titel of de titel is niet gevonden.')
                ->searchable(),
            TextColumn::make('created_at')
                ->label('Geplaatst op')
                ->sortable()
                ->date(),
        ];
    }

    /**
     * Defines the record-level interactions (View and Edit).
     *
     * The ViewAction is heavily customized to serve as the primapry reading interface.
     * It uses a modal schema that displays the full 'body' of the reaction.
     * A key feature here is the 'hintAction' on the 'insights_category' entry, whisch allows editors to classify the comment immediatly upon reading it.
     *
     * @return array<int, ViewAction|EditAction> List of actions.
     */
    private function getTableRecordActions(): array
    {
        return [
            ViewAction::make()
                ->modalIcon(Heroicon::OutlinedChatBubbleLeftRight)
                ->modalIconColor(self::$defaultColor)
                ->modalHeading(fn (Reaction $reaction): ?string => $reaction->title)
                ->modalDescription(fn (Reaction $reaction): string => __('Geplaatst door :user op :date', ['user' => $reaction->author, 'date' => $reaction->created_at->format('d F Y')]))
                ->modalCloseButton(false)
                ->schema([
                    TextEntry::make('insight_category')
                        ->hintAction($this->getHintActions())
                        ->label('Categorisering:')
                        ->formatStateUsing(fn(Reaction $reaction): string => $reaction->insight_category->getFullDisplayLabel())
                        ->columnSpanFull(),
                    TextEntry::make('body')
                        ->label('Reactie:')
                ]),

            EditAction::make()
        ];
    }

    /**
     * Creates a specialized action for inline metadata updates.
     *
     * This actions returns a Filament action intended to be triggered from within an infolist.
     * It opens a secondary modal specifically for selecting a new 'InsightCategory'.
     * This pattern reduces cognitive load by keeping the user focused on a single task: classifying the content they just read.
     *
     * Logic:
     * - Authorization: Checks the 'update' policy on theReaction modal
     * - Execution: Updates the model directly and returns a boolean status.
     *
     * @internal This action is integrated into the ViewAction infolist via hintAction()
     * @return Action The configured Filament action instance.
     */
    private function getHintActions(): Action
    {
        return Action::make('aanpassen')
            ->icon(Heroicon::OutlinedPencilSquare)
            ->authorize('update')
            ->modalHeading('Reactie categoriseren')
            ->modalDescription('U staat op een punt om een reactie te categoriseren. Het categoriseren van een reactie is niet vereist, maar het kan helpen bij de verrijking van artikelen.')
            ->modalWidth(Width::Medium)
            ->modalAlignment(Alignment::Center)
            ->modalIcon(Heroicon::Tag)
            ->modalIconColor(self::$defaultColor)
            ->badgeTooltip('test')
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalCancelAction(false)
            ->modalSubmitActionLabel('uitvoeren')
            ->action(fn (Reaction $reaction, array $data): bool => $reaction->update(['insight_category' => $data['insight_category']]))
            ->schema([
                Select::make('insight_category')
                    ->label('Insight categorie')
                    ->required()
                    ->native(false)
                    ->options(InsightCategory::class)
            ]);
    }
}
