<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources;

use App\Filament\Clusters\Articles;
use App\Filament\Clusters\Articles\Resources\LabelResource\Pages;
use App\Filament\Clusters\Articles\Resources\LabelResource\RelationManagers;
use App\Models\Label;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * LabelResource manages the CRUD operations for Labels in the Vlaams Woordenboek application.
 *
 * This Filament resource handles all label management functionality within the system. Labels are used
 * to categorize and organize dictionary articles, making them easier to find and manage. The resource
 * provides a complete interface for administrators to create, view, edit, and delete labels.
 *
 * @package App\Filament\Clusters\Articles\Resources
 */
final class LabelResource extends Resource
{
    /**
     * The underlying Eloquent model that represents labels in our database. This model handles all
     * database interactions and relationships with other models in the system. The Label model
     * contains the core business logic for label management.
     *
     * @var string|null
     */
    protected static ?string $model = Label::class;

    /**
     * The visual representation of this resource in the navigation menu. We use Heroicons for consistent
     * styling across the application. The tag icon was chosen as it best represents the labeling concept.
     * See https://heroicons.com for the complete icon set.
     *
     * @var string|null
     */
    protected static ?string $navigationIcon = 'heroicon-o-tag';

    /**
     * Organizational grouping for this resource. The Articles cluster contains all resources related to
     * dictionary article management, including labels. This helps maintain a logical structure in the
     * admin interface and groups related functionality together.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = Articles::class;

    /**
     * Configures the form interface for creating and editing labels. The form provides a user-friendly
     * interface with data validation and clear visual feedback. It uses a responsive 12-column grid
     * system to ensure proper layout across different screen sizes. Required fields are clearly marked,
     * and helpful placeholder text guides users through the input process.
     *
     * @param  Form $form  The Filament form builder instance used to construct the interface
     * @return Form        The fully configured form ready for rendering
     */
    public static function form(Form $form): Form
    {
        return $form
            ->columns(12)
            ->schema([
                Components\TextInput::make('name')
                    ->label('Label naam')
                    ->columnSpan(6)
                    ->unique(ignoreRecord: true)
                    ->required()
                    ->maxLength(255),
                Components\Textarea::make('description')
                    ->label('Beschrijving')
                    ->rows(4)
                    ->placeholder('Beschrijf zo goed mogelijk wat het label inhoud. (Optioneel)')
                    ->columnSpanFull()
            ]);
    }

    /**
     * Configures the detailed view interface for displaying label information.
     *
     * This method creates a structured information display with a clean, organized layout for viewing label details.
     * It presents the label's core attributes including name, timestamps, and description in a visually appealing format with consistent styling.
     * The interface uses the Filament design system, incorporating icons and responsive column layouts to ensure optimal presentation across different screen sizes.
     *
     * @param  Infolist $infolist   The Filament infolist builder instance
     * @return Infolist             The configured infolist ready for rendering
     */
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Label informatie')
                    ->collapsible()
                    ->description('Alle informatie omtrent het label dat is aangemaakt voor artikelen in het Vlaams Woordenboek')
                    ->icon('heroicon-o-tag')
                    ->iconSize(IconSize::Medium)
                    ->iconColor('primary')
                    ->compact()
                    ->columns(12)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Naam')
                            ->weight(FontWeight::Bold)
                            ->color('primary')
                            ->columnSpan(6),
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
                            ->placeholder('Geen label beschrijving geregistreed')
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
     * @param  Table $table  The Filament table builder instance
     * @return Table         The fully configured table interface
     */
    public static function table(Table $table): Table
    {
        return $table
            ->emptyStateIcon(self::$navigationIcon)
            ->emptyStateHeading('Geen labels gevonden')
            ->emptyStateDescription('Momenteel zijn er geen labels gevonden die aan woordenboek artikelen gekoppeld kunnen worden.')
            ->columns([
                TextColumn::make('name')
                    ->label('Label')
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
                    ->date()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->hiddenLabel(),
                Tables\Actions\EditAction::make()
                    ->hiddenLabel()
                    ->color('gray')
                    ->modalWidth(MaxWidth::SevenExtraLarge)
                    ->modalHeading('Label Wijzigen')
                    ->modalIcon('heroicon-o-pencil-square')
                    ->modalIconColor('gray')
                    ->modalDescription('U staat op het punt om een label te wijzigen voor het woordenboek en zijn artikels.'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()
                    ->icon('heroicon-o-trash')
                    ->modalDescription('Indien u het label verwijderd zal het label ook loskoppeld worden van de woorden. Bent u zeker dat u het label wilt verwijderen?'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->modalDescription('Indien u de geselecteeerde labels verwijderd zullen deze worden losgekoppeld van de woorden. Bent u zeker dat u de handeling wilt uitvoeren?'),
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
            RelationManagers\ArticlesRelationManager::class
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
            'index' => Pages\ListLabels::route('/'),
            'view' => Pages\ViewLabel::route('/{record}'),
        ];
    }
}
