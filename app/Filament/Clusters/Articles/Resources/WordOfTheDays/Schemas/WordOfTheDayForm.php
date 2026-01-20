<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;

/**
 * WordOfTheDayForm schema - the blueprint for our daily content curation.
 *
 * This class defines how editors interact with "Word of the Day" data within the admin panel.
 * It carefully structures the input fields to ensure that every entry is linked to a valid article, assigned to a unique date, and carries a meaningful reason for its selection.
 *
 * By centralizing this configuration, we maintain a consistent editorial experience across both the creation and editing workflows.
 *
 * @package App\Filament\Clusters\Articles\Resources\WordOfTheDays\Schemas
 */
final readonly class WordOfTheDayForm
{
    /**
     * Configures the structural layout and field logic for the Word of the Day form.
     *
     * This method assembles a compact, organized section called the "Planner."
     * It includes validation logic to prevent double-booking dates and filters available articles to ensure only published, disclaimer-free words are highlighted.
     *
     * @param  Schema $schema   The base schema instance to be enriched.
     * @return Schema           The fully configured schema containing the form components.
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Planner')
                    ->columns(12)
                    ->icon(Heroicon::OutlinedClock)
                    ->iconColor('primary')
                    ->columnSpanFull()
                    ->compact()
                    ->description('Koppel een woord aan een specifieke dag en gebeurtenis.')
                    ->schema([
                        /**
                         * The scheduling date picker.
                         * We ensure uniqueness here to guarantee that only one word can represent our dictionary on any given day.
                         */
                        DatePicker::make('scheduled_for')
                            ->label('Ingeplande datum')
                            ->required()
                            ->columnSpan(4)
                            ->unique(ignoreRecord: true)// This checks the DB before submitting
                            ->native(false),

                        /**
                         * The article selection dropdown.
                         * This searches through our dictionary articles, filtering out drafts or items requiring specific legal disclaimers.
                         */
                        Select::make('article_id')
                            ->label('Selecteer Woord')
                            ->relationship(
                                name: 'article', 
                                titleAttribute: 'word', 
                                modifyQueryUsing: fn (Builder $query) => $query->where('disclaimer_id', null)->whereNotNull('published_at')
                            )
                            ->searchable()
                            ->columnSpan(8)
                            ->preload()
                            ->required(),

                        /**
                         * The justification for the selection.
                         * This provides context for the editor's choice, linking  the word to current events or seasons.
                         */
                        Textarea::make('scheduling_reason')
                            ->columnSpanFull()
                            ->label('Gebeurtenis / Aanleiding')
                            ->placeholder('Bijv: Internationale Vrouwendag of Start van de Lente')
                            ->rows(5)
                            ->helperText('Waarom is dit het woord van de dag?'),
                    ])  
            ]);
    }
}
