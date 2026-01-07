<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas;

use App\Filament\Resources\Articles\ArticleResource;
use App\Models\WordOfTheDay;
use Filament\Actions\Action;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;

/**
 * WordOfTheDaysInfolist - the read-only display for our scheduled linguistic gems.
 *
 * This class defines the "Infolist" or view-only layout used to inspect a "Word of the Day" entry.
 * While the form is for editing, this schema is focused on clarity and readability, presenting 
 * the administrative details and the editorial reasoning behind a selection in a clean, 
 * non-interactive format.
 *
 * It provides a quick way for team members to verify who scheduled a word, which word was chosen, and the specific occasion it commemorates.
 *
 * @package App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas
 */
final readonly class WordOfTheDaysInfolist
{
    /**
     * Configures the visual arrangement of the information display.
     *
     * This method maps our database attributes to a 12-column grid. 
     * It highlights the selected word in bold primary colors and uses tooltips to provide relative time context (e.g., "3 days from now") for the scheduling dates.
     *
     * @param  Schema $schema   The base schema instance to be configured.
     * @return Schema           The configured infolist containing the read-only components.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(12)
            ->components(components: [
                /**
                 * Displays the name of the editor who performed the scheduling.
                 * We use the user circle icon to personify the editorial action.
                 */
                TextEntry::make('planner.name')
                    ->label('Ingepland door')
                    ->icon(Heroicon::OutlinedUserCircle)
                    ->columnSpan(3)
                    ->iconColor('primary'),

                /**
                 * The centerpiece: the actual word being featured.
                 * Styled with bold weight and primary branding to draw the eye immediately.
                 */
                TextEntry::make('article.word')
                    ->label('Artikel')
                    ->url(fn (WordOfTheDay $wordOfTheDay): string => ArticleResource::getUrl('view', ['record' => $wordOfTheDay->article]))
                    ->weight(FontWeight::Bold)
                    ->columnSpan(3)
                    ->color('primary'),

                /**
                 * The target date for the publication.
                 * Includes a relative tooltip to help editors see how far away the date is.
                 */
                TextEntry::make('scheduled_for')
                    ->label('Ingepland voor')
                    ->date()
                    ->columnSpan(3)
                    ->sinceTooltip(),

                /**
                 * The audit timestamp for when this entry was first created.
                 */
                TextEntry::make('created_at')
                    ->label('Ingepland op')
                    ->date()
                    ->columnSpan(3)
                    ->sinceTooltip(),

                /**
                 * The editorial context or event that prompted this selection.
                 * Given a full-width span to accommodate longer descriptions or stories.
                 */
                TextEntry::make('scheduling_reason')
                    ->columnSpan(12)
                    ->label('Gebeurtenis / Aanleiding')
                    ->placeholder('- geen aanleiding of gebeurtenis geregistreerd'),
            ]);
    }
}