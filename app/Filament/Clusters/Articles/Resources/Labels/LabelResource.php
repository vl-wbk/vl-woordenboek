<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\Labels;

use App\Attributes\Todo;
use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\Labels\Pages\ListLabels;
use App\Filament\Clusters\Articles\Resources\Labels\Pages\ViewLabel;
use App\Filament\Clusters\Articles\Resources\Labels\RelationManagers\ArticlesRelationManager;
use App\Filament\Support\Concerns\HasActiveIcon;
use App\Models\Label;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * LabelResource manages the CRUD operations for Labels in the Vlaams Woordenboek application.
 *
 * This Filament resource handles all label management functionality within the system. Labels are used
 * to categorize and organize dictionary articles, making them easier to find and manage. The resource
 * provides a complete interface for administrators to create, view, edit, and delete labels.
 *
 * @package App\Filament\Clustsers\Articles\Resources\Labels
 */
#[Todo(message: 'Implement translation systeem voor deze resource', prtiority: 'critical')]
#[Todo(message: 'Make this resource cleaner by breaking up this rezsource to their respective schema classes')]
final class LabelResource extends Resource
{
    use HasActiveIcon;

    /**
     * The underlying Eloquent model that represents labels in our database. This model handles all
     * database interactions and relationships with other models in the system. The Label model
     * contains the core business logic for label management.
     */
    protected static ?string $model = Label::class;

    /**
     * The visual representation of this resource in the navigation menu. We use Heroicons for consistent
     * styling across the application. The tag icon was chosen as it best represents the labeling concept.
     * See https://heroicons.com for the complete icon set.
     */
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static string|UnitEnum|null $navigationGroup = 'Gegevens';

    /**
     * Organizational grouping for this resource. The Articles cluster contains all resources related to
     * dictionary article management, including labels. This helps maintain a logical structure in the
     * admin interface and groups related functionality together.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * Configures the form interface for creating and editing labels. The form provides a user-friendly
     * interface with data validation and clear visual feedback. It uses a responsive 12-column grid
     * system to ensure proper layout across different screen sizes. Required fields are clearly marked,
     * and helpful placeholder text guides users through the input process.
     *
     * @param  \Filament\Schemas\Schema  $schema  The Filament form builder instance used to construct the interface
     * @return \Filament\Schemas\Schema The fully configured form ready for rendering
     */
    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components([
                TextInput::make('type')
                    ->label('Type')
                    ->translateLabel()
                    ->required()
                    ->maxLength(255)
                    ->columnSpan(3),
                TextInput::make('name')
                    ->label('Label naam')
                    ->columnSpan(6)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Beschrijving')
                    ->rows(4)
                    ->placeholder('Beschrijf zo goed mogelijk wat het label inhoud. (Optioneel)')
                    ->columnSpanFull(),

                Toggle::make('private')
                    ->columnSpanFull()
                    ->label('Dit label is enkel voor interne doeleinden.')
            ]);
    }

    /**
     * Configures the detailed view interface for displaying label information.
     *
     * This method creates a structured information display with a clean, organized layout for viewing label details.
     * It presents the label's core attributes including name, timestamps, and description in a visually appealing format with consistent styling.
     * The interface uses the Filament design system, incorporating icons and responsive column layouts to ensure optimal presentation across different screen sizes.
     *
     * @param  \Filament\Schemas\Schema  $schema  The Filament infolist builder instance
     * @return \Filament\Schemas\Schema The configured infolist ready for rendering
     */
    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Label informatie')
                    ->collapsible()
                    ->description('Alle informatie omtrent het label dat is aangemaakt voor artikelen in het Vlaams Woordenboek')
                    ->icon('heroicon-o-tag')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->compact()
                    ->columns(12)
                    ->columnSpanFull()
                    ->schema([
                        IconEntry::make('private')
                            ->label('Intern label')
                            ->boolean()
                            ->columnSpan(2),

                        TextEntry::make('name')
                            ->label('Naam')
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->columnSpan(4),
                        TextEntry::make('created_at')
                            ->label('Aangemaakt op')
                            ->columnSpan(3)
                            ->date(),
                        TextEntry::make('updated_at')
                            ->label('Laaste wijziging')
                            ->columnSpan(3)
                            ->date(),
                        TextEntry::make('description')
                            ->label('Beschrijving')
                            ->columnSpanFull()
                            ->placeholder('Geen label beschrijving geregistreerd'),
                    ]),
            ]);
    }

    /**
     * Defines the table interface for managing labels. The table provides a comprehensive view of all
     * labels in the system with sorting, searching, and bulk action capabilities. Each row includes
     * key information about the label and quick access to common actions. Empty states provide clear
     * feedback when no labels exist.
     *
     * The interface includes real-time updates for article counts and optimized performance through
     * strategic database queries. Modal dialogs ensure safe deletion with clear warning messages.
     *
     * @param  Table  $table  The Filament table builder instance
     * @return Table The fully configured table interface
     */
    public static function table(Table $table): Table
    {
        return $table
            ->striped()
            ->deferLoading()
            ->heading('Label overzicht')
            ->description('Overzicht van alle labels die gekoppeld kunnen worden aan artikelen in het Vlaams Woordenboek')
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen labels gevonden')
            ->emptyStateDescription('Momenteel zijn er geen labels gevonden die aan woordenboek artikelen gekoppeld kunnen worden.')
            ->columns([
                TextColumn::make('name')
                    ->label('Label')
                    ->icon(Heroicon::OutlinedTag)
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('articles_count')
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->label('Aantal koppelingen')
                    ->counts('articles'),
                TextColumn::make('description')
                    ->label('Beschrijving')
                    ->placeholder('- geen beschrijving opgegeven')
                    ->formatStateUsing(fn (Label $label): string => Str::limit($label->description, 60, '...', preserveWords: true)),
                TextColumn::make('created_at')
                    ->label('Aangemaakt op')
                    ->sortable()
                    ->date(),
            ])
            ->recordActions([
                ViewAction::make(),

                ActionGroup::make([
                    EditAction::make()
                        ->hiddenLabel()
                        ->color('gray')
                        ->modalWidth(Width::SevenExtraLarge)
                        ->modalHeading('Label Wijzigen')
                        ->modalIcon('heroicon-o-pencil-square')
                        ->modalIconColor('gray')
                        ->modalDescription('U staat op het punt om een label te wijzigen voor het woordenboek en zijn artikels.'),

                    ActionGroup::make([
                        DeleteAction::make()->hiddenLabel()
                            ->icon('heroicon-o-trash')
                            ->modalDescription('Indien u het label verwijderd zal het label ook losgekoppeld worden van de woorden. Bent u zeker dat u het label wilt verwijderen?'),
                    ])->dropdown(false)
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->modalDescription('Indien u de geselecteerde labels verwijderd zullen deze worden losgekoppeld van de woorden. Bent u zeker dat u de handeling wilt uitvoeren?'),
                ]),
            ]);
    }

    /**
     * Provides a dynamic count of labels in the navigation menu. This method implements a flexible
     * caching strategy to balance performance with data freshness. The cache updates frequently
     * enough to maintain accuracy while reducing database load. The badge helps administrators
     * quickly gauge the size of the labeling system.
     *
     * @return string|null The current label count, or null if no labels exist
     */
    public static function getNavigationBadge(): ?string
    {
        return Cache::flexible('label_count', [10, 60], fn (): string => (string) self::$model::count());
    }

    /**
     * Configures the available pages for this resource.
     * The page structure determines how users navigate through the label management interface.
     * Currently implements a list view for all labels and detailed views for individual labels.
     * Create and edit operations are handled through modal dialogs for a smoother user experience.
     *
     * This interface is crucial for maintaining the taxonomic structure of the dictionary, allowing organized categorization and easy navigation of related words.
     *
     * @return array<mixed> The configured relation managers, currently containing only the Words relationship
     */
    public static function getRelations(): array
    {
        return [
            ArticlesRelationManager::class,
        ];
    }

    /**
     * Configures the available pages for this resource. The page structure determines how users
     * navigate through the label management interface. Currently implements a list view for all
     * labels and detailed views for individual labels. Create and edit operations are handled
     * through modal dialogs for a smoother user experience.
     *
     * @return array<mixed> The configured page routes and their handlers
     */
    public static function getPages(): array
    {
        return [
            'index' => ListLabels::route('/'),
            'view' => ViewLabel::route('/{record}'),
        ];
    }
}
