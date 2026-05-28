<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use App\Models\WordOfTheDay;
use CodeWithDennis\FactoryAction\FactoryAction;
use Filament\Actions\ActionGroup;
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
     * We've grouped our header actions into a cohesive button group to keep the interface tidy.
     * This includes our primary scheduling tool and a specialized factory action for
     * generating test data, ensuring that the workflow remains intuitive for both
     * production curation and development testing.
     *
     * @return array<int, ActionGroup> The list of action groups to render in the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                /**
                 * The primary scheduling action.
                 * We've customized this to emphasize the temporal aspect of the task, using a subtle gray color and a clock icon to distinguish it from standard record creation.
                 */
                CreateAction::make()
                    ->color('gray')
                    ->icon(Heroicon::OutlinedClock)
                    ->visible($this->canDisplayActionButton())
                    ->label('Woord v/d dag inplannen'),

                /**
                 * The factory testing action.
                 * This tool allows developers and editors to rapidly populate the schedule with mock data. It features a safety modal to prevent accidental mass-generation in a live environment.
                 */
                FactoryAction::make()
                    ->modalHeading('Genereer woorden van de dag')
                    ->modalDescription('Door het onderstaande formulier in te vullen kun je woorden van de dag genereren om het systeem te testen. Ben je zeker dat je wilt doorgaan?')
            ])->buttonGroup()
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
