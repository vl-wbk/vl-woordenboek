<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

/**
 * EditWordOfTheDay page - where we refine and polish our daily highlights.
 *
 * This page provides the interface for editors to modify existing "Word of the Day" entries.
 * Whether it's correcting a typo, updating the scheduled date, or adjusting the content, this is the workspace for ensuring our linguistic data remains accurate and engaging.
 *
 * It also serves as the final checkpoint where entries can be removed from the schedule if they are no longer suitable for publication.
 *
 * @package App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages
 */
final class EditWordOfTheDay extends EditRecord
{
    /**
     * The resource class that this page belongs to.
     * This maintains the structural link to the main Word of the Day management hub.
     *
     * @var class-string<WordOfTheDayResource>
     */
    protected static string $resource = WordOfTheDayResource::class;

    /**
     * Defines the actions available in the header of the edit page.
     *
     * We include a delete action here to allow editors to easily remove a record directly from the editing interface. 
     * We've customized it with an outlined trash icon to stay consistent with our visual language.
     *
     * @return array<int, DeleteAction> The list of actions to render in the page header.
     */
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->icon(Heroicon::OutlinedTrash),
        ];
    }
}
