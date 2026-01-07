<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use App\Models\WordOfTheDay;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;

/**
 * ListWordOfTheDays page - the bird's-eye view of our linguistic calendar.
 *
 * This page provides a comprehensive overview of all scheduled "Word of the Day" entries.
 * It serves as the central hub where editors can monitor the publication queue, search through past highlights, and identify gaps in the upcoming schedule.
 *
 * The interface is designed to make management efficient, offering quick access to creation and filtering tools so our daily Flemish insights remain consistent.
 *
 * @package App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages
 */
final class ListWordOfTheDays extends ListRecords
{
    /**
     * The resource class that this page belongs to.
     * This links the list view back to the core Word of the Day resource definition.
     *
     * @var class-string<WordOfTheDayResource>
     */
    protected static string $resource = WordOfTheDayResource::class;

    /**
     * Defines the primary actions available at the top of the list view.
     *
     * We've customized the creation action to emphasize the scheduling aspect, using a clock icon and a specific Dutch label. 
     * The visibility of this action is tied to the current state of our database to ensure a logical workflow.
     *
     * @return array<int, CreateAction> The list of actions to render in the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->icon(Heroicon::OutlinedClock)
                ->visible($this->canDisplayActionButton())
                ->label('Woord v/d dag inplannen'),
        ];
    }

    /**
     * Determines whether the "Schedule" action should be presented to the editor.
     *
     * This logic acts as a visual guard; we only show the primary action button when there is existing data, 
     * guiding the user toward the appropriate interaction based on the current volume of content.
     *
     * @return bool True if the action button should be rendered, false otherwise.
     */
    private function canDisplayActionButton(): bool 
    {
        return WordOfTheDay::count() > 0;
    }
}
