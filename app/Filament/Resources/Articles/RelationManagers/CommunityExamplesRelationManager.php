<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Models\Article;
use A909M\FilamentStateFusion\Actions\StateFusionAction;
use A909M\FilamentStateFusion\Actions\StateFusionBulkAction;
use A909M\FilamentStateFusion\Tables\Filters\StateFusionSelectFilter;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Actions\MigrateExamplesAction;
use App\Filament\Clusters\Articles\Resources\ExampleSentences\Schema\ExampleSentenceForm;
use App\Filament\Clusters\Volunteers\Resources\VolunteerPositions\Actions\CreateAction;
use App\Filament\Resources\Articles\Pages\ViewWord;
use App\Policies\UserExamplePolicy;
use App\States\ExampleSentence\Approved;
use App\States\ExampleSentence\Pending;
use App\States\ExampleSentence\Rejected;
use App\States\ExampleSentence\Unpublished;
use BackedEnum;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * CommunityExamplesRelationManager: Orchestrates the moderation lifecycle of crowdsourced content.
 *
 * ! DESIGN PHILOSOPHY:
 * This component acts as a "Buffer Zone" between public submissions and the dictionary's core dataset.
 * It Leverages a State Machine pattern (via FilamantStateFusion) to ensure data integrity during
 * the transition from 'Pending' to 'Approved' or 'Rejected'.
 *
 * ! ARCHITECTURAL GUIDELINES:
 * - Single source of truth: Form schemas are delegated to ExampleSentenceForm to prevent schema drift.
 * - Explicit Authorization: Logic is decoupled into UserExamplePolicy using static constants.
 * - Contextual Constraints: Restricted strictly to the ViewWord context to optimize resource load.
 *
 * @package App\Filament\Resources\Articles\RelationManagers
 */
final class CommunityExamplesRelationManager extends RelationManager
{
    /** @var string $relationship - The Eloquent relation ship name on the parent Article model. */
    protected static string $relationship = 'userExamples';

    /** @var string|null $title - Semantic UI title. */
    protected static ?string $title = 'Voorbeeldzinnen';

    /** @var string|BackedEnum|null $icon Standardized icon for conversational content. */
    protected static string|BackedEnum|null $icon = Heroicon::OutlinedChatBubbleLeftRight;

    /**
     * Toggles the read-only state of the manager.
     * MUST return false to enable the moderation actions defined in the table.
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Determines the visibility of this relation manager within the Filament View page of the Article resource.
     *
     * To optimize performance, this manager is restricted to the 'ViewRecord' page
     * preventing unnecessary relationship queries on high-traffic index or edit pages.
     *
     * @param  Article    $ownerRecord  The parent Article record.
     * @param  string     $pageClass    The class name of the current Filament page.
     * @return bool
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    /**
     * Generates a numeric badge for the relation tab.
     *
     * Provides immediate visual feedback to the volunteer regardin the volume of
     * community contributions wiothout requiring an active tab switch.
     *
     * @param  Article    $ownerRecord  The parent Article record.
     * @param  string     $pageClass    The class name of the current Filament page.
     * @return string|null
     */
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        $count = $ownerRecord->userExamples->count();

        return $count > 0 ? (string) $count : null;
    }

    /**
     * Configures the CRUD schema.
     *
     * This method delegates to a static configuration to enforce a Single Source of Truth
     * for the UserExample data structure.
     *
     * @param  Schema $schema The schema instance that needs to be configured.
     * @return Schema         The configured schema instance.
     */
    public function form(Schema $schema): Schema
    {
        return ExampleSentenceForm::configure($schema);
    }

    /**
     * High-lpevel table orchestration. Not the use of private helper methods to keep the main configuration block readable and maintainable.
     * The table is devided into logical action zones (Header, Toolbar, Record) To maintain a clean and scalable configuration.
     *
     * @param  Table $table The table instance that needs to be configured.
     * @return Table        The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading(heading: 'Community voorbeeldzinnen')
            ->description(description: 'Voorbeeldzinnen die zijn bijgedragen door de gebruikers van het Vlaams Woordenboek')
            ->headerActions(actions: $this->registerHeaderActions())
            ->emptyStateIcon(self::$icon)
            ->emptyStateHeading(heading: 'Geen voorbeeldzinnen gevonden')
            ->emptyStateDescription(description: 'Momenteel zijn er geen voorbeeldzinnen gevonden die door de community zijn bijgedragen.')
            ->columns(components: $this->registerTableComponents())
            ->toolbarActions(actions: $this->registerToolbarActions())
            ->filters(filters: $this->registerTableFilters())
            ->recordActions(actions: $this->registerRecordActions());
    }

    /**
     * Registers high-volume moderation actions.
     *
     * These actions allow volunteers to process multiple submissions simultaneously,
     * strictly following the state transition logic from Pending to Approved.
     *
     * @return BulkActionGroup[]
     */
    private function registerToolbarActions(): array
    {
        return [
            BulkActionGroup::make([
                StateFusionBulkAction::make('approve')
                    ->authorize(UserExamplePolicy::changeStateAny)
                    ->label('Goedkeuren')
                    ->icon(Heroicon::OutlinedCheckBadge)
                    ->modalCloseButton(false)
                    ->deselectRecordsAfterCompletion()
                    ->outlined()
                    ->modalHeading('Voorbeelzinnen goedkeuren')
                    ->modalDescription('U staat op het punt om community voorbeeldzinnen goed te keuren in het Vlaams Woordenboek. Weet je zeker dat je wilt uitvoeren?')
                    ->transition(Pending::class, Approved::class),

                ActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalHeading('Geselecteerde voorbeeldzinnen verwijderen'),
                ])->dropdown(false)
            ])
        ];
    }

    /**
     * Defines the state-aware filters for the data table.
     *
     * Provides an interface for volunteers to isolate records by their current lifecycle stage,
     * utilizing the StateFusion filter to automatically populate options based on the available model states.
     *
     * @return StateFusionSelectFilter[] An array of filters for the table.
     */
    private function registerTableFilters(): array
    {
        return [
            StateFusionSelectFilter::make('status')
                ->native(false),
        ];
    }

    /**
     * Registers discrete actions available for each invidual record.
     *
     * These actions encapsulate the core moderation workflow, providing granulary
     * control over the lifecycle of a single contribution. Each action is
     * strictly guarded by individual-level authorization checks via de policy.
     *
     * @return array<EditAction|StateFusionAction> The list of actions for each table row.
     */
    private function registerRecordActions(): array
    {
        return [
            StateFusionAction::make('approve')
                ->authorize(UserExamplePolicy::changeState)
                ->label('Publiceren')
                ->icon(Heroicon::OutlinedCheckBadge)
                ->transitionTo(Approved::class),

            EditAction::make()
                ->modalHeading('Community voorbeeldzin bewerken')
                ->modalIcon(Heroicon::OutlinedPencilSquare)
                ->modalDescription('Staat er een typo in de voorbeeldzin? Geen probleem u kunt deze oplossen door het onderstaande formulier.')
                ->modalCloseButton(false),

            StateFusionAction::make('reject')
                ->authorize(UserExamplePolicy::changeState)
                ->label('Afwijzen')
                ->icon(Heroicon::XMark)
                ->transitionTo(Rejected::class),

            StateFusionAction::make('unpublish')
                ->label('Offline halen')
                ->authorize(UserExamplePolicy::changeState)
                ->icon(Heroicon::OutlinedEyeSlash)
                ->transitionTo(Unpublished::class),

        ];
    }

    /**
     * Defines the visual presentation and data mapping for the table columns.
     *
     * This layout prioritizes identifying the contributor and the current status
     * at a glance, with the actual example text provided in a searchable format
     * for quick editorial review.
     *
     * @return TextColumn[] The column configuration for the Filament table.
     */
    private function registerTableComponents(): array
    {
        return [
            TextColumn::make('author.name')
                ->label('Ingezonden door')
                ->icon(Heroicon::OutlinedUserCircle)
                ->weight(FontWeight::Bold)
                ->iconColor('primary')
                ->color('primary')
                ->sortable()
                ->searchable(),

            TextColumn::make('status')
                ->sortable()
                ->badge(),

            TextColumn::make('example')
                ->label('Voorbeeldzin')
                ->limit(90)
                ->searchable(),

            TextColumn::make('created_at')
                ->label('Geregistreerd op')
                ->date()
                ->sortable(),
        ];
    }

    /**
     * Registers global actions available in the relation manager header.
     *
     * Includes utilities for data migration (converting community examples into
     * permanent dictionary records) and manual entry for volunteer-facilitated
     * contributions.
     *
     * @return array<CreateAction|MigrateExamplesAction> Header-level management actions.
     */
    private function registerHeaderActions(): array
    {
        return [
            MigrateExamplesAction::make(),

            CreateAction::make()
                ->label('Voorbeelzin toevoegen')
                ->modalHeading('Voorbeeldzin toevoegen in het Vlaams Woordenboek')
                ->modalDescription('Met voorbeeldzinnen worden het gebruik van het woord beter zichtbaar.')
                ->modalIcon(Heroicon::OutlinedPlusCircle)
                ->modalIconColor('primary'),
        ];
    }
}
