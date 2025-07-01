<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\ArticleResource\RelationManagers;

use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema;
use App\Filament\Clusters\Articles\Resources\EtymologyResource\Schema\TableSchema;
use App\Filament\Resources\ArticleResource\Pages\ViewWord;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * @todo Document this relation manager.
 */
final class EtymologyRelationManager extends RelationManager
{
    protected static string $relationship = 'etymology';

    protected static ?string $icon = 'heroicon-o-clock';

    protected static ?string $title = 'Etymologie';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    public function form(Form $form): Form
    {
        return Schema\FormSchema::configure($form);
    }

    public function table(Table $table): Table
    {
        return $table
            ->description(description: 'De etymologie beschrijft de herkomst en geschiedenis van een woord. In deze sectie ontdek je hoe een woord is ontstaan, uit welke taal het is overgenomen, en hoe het zich in de loop van de tijd heeft ontwikkeld. We verwijzen daarbij naar verwante vormen in andere talen, historische spellingswijzen en oorspronkelijke betekenissen. Zo krijg je inzicht in de wortels van het woord en de weg die het heeft afgelegd naar het huidige gebruik in het Nederlands.')
            ->emptyStateIcon(icon: self::$icon)
            ->emptyStateHeading(heading: 'Geen gegevens gevonden')
            ->emptyStateDescription(description: 'Er zijn geen gevens gevonden voor de etymologie van het woord')
            ->columns(components: TableSchema::configureColumns())
            ->filters(filters: TableSchema::configureFilters())
            ->filtersFormWidth(width: MaxWidth::Medium)
            ->actions(actions: TableSchema::configureActions())
            ->bulkActions(actions: TableSchema::configureBulkActions())
            ->headerActions(actions: TableSchema::configureHeaderActions($this->ownerRecord));
    }
}
