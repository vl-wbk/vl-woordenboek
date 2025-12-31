<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Articles\Pages\ViewWord;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use BackedEnum;

/**
 * Relation Manager for the 'related' Article relationship.
 *
 * This class manages the display, creation, and modification of articles related to a parent Article model (often representing a 'word' or main entry).
 * It is specifically configured to only be active when viewing the parent record via the custom ViewWord page, preventing its appearance on standard View or Edit pages.
 *
 * @package App\Filament\Resources\Articles\RelationManagers
 */
final class RelatedRelationManager extends RelationManager
{
    /**
     * The name of the Eloquent relationship on the parent model (Article).
     * This relationship is typically defined as a Many-to-Many on the Article model.
     *
     * @see \App\Models\Article::related()
     */
    protected static string $relationship = 'related';

    /**
     * The title displayed above the relation table in the Filament UI.
     */
    protected static ?string $title = 'Gerelateerde artikelen';

    /**
     * The icon displayed next to the title.
     */
    protected static string|BackedEnum|null $icon = Heroicon::OutlinedBookOpen;

    /**
     * The Filament Resource class that corresponds to the related records.
     * Used for routing to the view/edit pages of the related articles.
     *
     * @var class-string<ArticleResource>
     */
    protected static ?string $relatedResource = ArticleResource::class;

    /**
     * Determines whether the relation manager allows modifications (create, attach, detach, edit).
     * Returns false to allow all standard table actions.
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return false;
    }

    /**
     * Restricts the visibility of this relation manager to specific Filament pages.
     *
     * This manager is only enabled when the parent record is being viewed via the custom ViewWord page,
     * preventing its display on the standard view/edit pages.
     *
     * @param  Model  $ownerRecord  The instance of the parent model (article)
     * @param  string $pageClass    The class name of the current Filament page.
     * @return bool                 True if the current page is \App\Filament\Resources\Articles\Pages\ViewWord.
     */
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    /**
     * Defines the table structure, columns, filters, and actions for the related articles list.
     *
     * Future developers should add column definitions here (e.g., ID, Title, Status)
     * and any necessary table actions (e.g., detach, edit, view).
     *
     * @param  Table $table The table builder instance.
     * @return Table        The configured table instance.
     */
    public function table(Table $table): Table
    {
        return $table->defaultSort('word');
    }
}
