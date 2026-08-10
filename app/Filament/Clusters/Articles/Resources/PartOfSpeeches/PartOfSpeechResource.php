<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\PartOfSpeeches;

use App\Filament\Clusters\Articles\ArticlesCluster;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Pages;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Schemas\PartOfSpeechForm;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Schemas\PartOfSpeechInfolist;
use App\Filament\Clusters\Articles\Resources\PartOfSpeeches\Tables\PartOfSpeechesTable;
use App\Models\PartOfSpeech;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Class PartOfSpeechResource
 *
 * This class acts as the central management hub for the "PartOfSpeech" model within the Filament admin panel.
 * It integrates the resource into the "Articles" cluster and organizes it under the "Ondersteuning" navigation group.
 * By delegating UI logic to specialized Table and Schema classes, it maintains a clean separation of concerns for linguistic category management.
 *
 * @package App\Filament\Clusters\Articles\Resources\PartOfSpeeches
 */
final class PartOfSpeechResource extends Resource
{
    /**
     * The Eloquent model associated with this resource.
     *
     * {@inheritDoc}
     */
    protected static ?string $model = PartOfSpeech::class;

    /**
     * The icon displayed in the sidebar nabigation.
     *
     * @var string|BackedEnum|null
     */
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    /**
     * The cluster group this resource belongs to for logical organization.
     *
     * {@inheritDoc}
     */
    protected static ?string $cluster = ArticlesCluster::class;

    /**
     * The localized signular label for the resource.
     *
     * @var string|null
     */
    protected static ?string $modelLabel = 'Woordsoort';

    /**
     * The localized plural label used in the navigation and headers.
     *
     * @var string|null
     */
    protected static ?string $pluralModelLabel = 'Woordsoorten';

    /**
     * The sidebar group title where this resource is listed.
     *
     * @var string|null|UnitEnum
     */
    protected static string|null|UnitEnum $navigationGroup = 'Ondersteuning';

    /**
     * Define the data entry and edit form.
     * Delegates the form component definitions to the PartOfSpeechForm factory class to keep the resource definition lightweight.
     *
     * @param  Schema $schema  The incoming form schema instance.
     * @return Schema          The configured form schema for editing parts of speech
     */
    public static function form(Schema $schema): Schema
    {
        return PartOfSpeechForm::configure($schema);
    }

    /**
     * Define the data listing table.
     * Delegates the column and action definitions to the PartOfSpeechesTable factory class, ensuring that the list view logic is encapsulated and reusable.
     *
     * @param  Table $table  The incoming form schema instance.
     * @return Table         The configured form schema for editing parts of speech.
     */
    public static function table(Table $table): Table
    {
        return PartOfSpeechesTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartOfSpeechInfolist::configure($schema);
    }

    /**
     * Map the resource to specific pages.
     * Defines the URL strucutre and the correspondinbg Filament Page components used fo listing, creating, and editing records.
     *
     * @return array<string, \Filament\Resources\Pages\PageRegistration> An associative array of routes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartOfSpeeches::route('/'),
            'view' => Pages\ViewPartOfSpeeches::route('/{record}'),
        ];
    }
}
