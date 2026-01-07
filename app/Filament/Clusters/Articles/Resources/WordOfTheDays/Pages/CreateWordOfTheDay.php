<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages;

use App\Filament\Clusters\Articles\Resources\WordOfTheDays\WordOfTheDayResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

/**
 * CreateWordOfTheDay page - the starting point for growing our daily vocabulary.
 *
 * This page provides the interface for editors to introduce new "Word of the Day" entries into the system. 
 * It handles the initial validation and preparation of the data before it is committed to our database.
 * We've customized this process to ensure that every new entry is automatically attributed to the editor who created it, maintaining a clear audit trail of our editorial contributions.
 *
 * @package App\Filament\Clusters\Articles\Resources\WordOfTheDays\Pages
 */
final class CreateWordOfTheDay extends CreateRecord
{
    /**
     * The resource class that this page belongs to; 
     * This establishes the link back to the main wWord of the management hub. 
     *
     * @var class-string<WordOfTheDayResource>
     */
    protected static string $resource = WordOfTheDayResource::class;

    /**
     * The custom title displayed at the top of the creation page. 
     * We use a specific Dutch label to make the editorial intent clear: "Scheduling a Word of the Day".
     *
     * @var string|null
     */
    protected static ?string $title = "Woord van de dag inplannen";

    /**
     * Determines whether the "Create & create another" button is visible.
     * We've disabled this to encourage editors to focus on the quality and scheduling of one word at a time.
     * 
     * @var bool
     */
    protected static bool $canCreateAnother = false;

    /**
     * Customizes the primary action button for the creation form.
     * We've swapped the standard "Create" label for "Inplannen" (Schedule) and added an inviting icon to better reflect the editorial workflow.
     *
     * @return Action The modified action configuration for the submit button.
     */
    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->icon(Heroicon::OutlinedPlusCircle)
            ->label('Inplannen');
    }

    /**
     * Intercepts the form data before the record is actually created in the database.
     * This is where we silently inject the ID of the currently authenticated user, ensuring we always know who was responsible for scheduling the word.
     *
     * @param  array<string, mixed> $data   The raw data collected from the form fields.
     * @return array<string, mixed>         The enriched data array including the scheduler's ID.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var User $user */
        $user = Auth::user();

        $data['scheduled_by'] = $user->id;

        return $data;
    }
}
