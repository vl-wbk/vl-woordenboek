<?php

namespace App\Filament\Resources\Articles\RelationManagers;

use App\Filament\Resources\Articles\ArticleResource;
use App\Filament\Resources\Articles\Pages\ViewWord;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RelatedRelationManager extends RelationManager
{
    protected static string $relationship = 'related';

    /**
     * Sets the display title in the admin interface to "Notities" (Dutch for notes).
     * This localization choice reflects the application's primary language setting.
     */
    protected static ?string $title = 'Gerelateerde artikelen';

    /**
     * Sets the icon to be displayed for the NotesRelationManager in the Filament admin panel.
     */
    protected static string | \BackedEnum | null $icon = 'heroicon-o-book-open';

    public function isReadOnly(): bool
    {
        return false;
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === ViewWord::class;
    }

    protected static ?string $relatedResource = ArticleResource::class;

    public function table(Table $table): Table
    {
        return $table;
    }
}
