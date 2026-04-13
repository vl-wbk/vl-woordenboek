<?php

declare(strict_types=1);

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\Schema\{ArticleForm, FormSchema};
use App\Models\Article;
use App\Enums\ArticleStates;
use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\{Section, Wizard};
use Filament\Schemas\Components\Wizard\Step;
use Filament\Support\Icons\Heroicon;

/**
 * CreateWord Class
 *
 * A specialized form handler for creating new word entries in the Flemish dictionary.
 * This class implements a wizard-style interface to guide users through the word creation
 * process. It builds upon Filament's CreateRecord functionality to deliver a structured,
 * multi-step approach for adding new dictionary entries.
 *
 * The form guides users through two distinct steps. The first step, "General Information",
 * captures the essential details about the word itself. The second step, "Region & Status",
 * collects geographical context and publication status information. This separation helps
 * organize the data entry process in a logical and user-friendly manner.
 *
 * @property Article $record The database entity from the created dictionary article.
 *
 * @package App\Filament\Resources\ArticleResource\Pages
 */
final class CreateWord extends CreateRecord
{
    use HasWizard;

    /**
     * The resource class associated with this form handler.
     * Connects this form to the ArticleResource for proper data management.
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Form Configuration Method
     *
     * Constructs and configures the wizard-style form interface. This method establishes
     * the core structure of the form, implementing a full-width layout with multiple steps.
     * The configuration includes essential wizard features such as step navigation,
     * action buttons for cancellation and submission, and step skipping capabilities.
     * The form uses a fluid layout without container constraints for maximum flexibility.
     *
     * @param \Filament\Schemas\Schema $schema The base form instance to be enhanced with wizard functionality
     * @return \Filament\Schemas\Schema The fully configured form with wizard implementation
     */
    public function form(Schema $schema): Schema
    {
        return parent::form($schema)
            ->components([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])->columns(null);
    }

    /**
     * Post-Creation handles
     *
     * Executes automatically after successful word creation. This method handles the crucial
     * task of establishing ownership by creating a relationship between the newly created
     * word entry and the currently authenticated user. This association ensures proper
     * attribution and maintains data integrity within the dictionary system.
     */
    public function afterCreate(): void
    {
        $this->record->author()->associate(auth()->user())->save();

        if ($this->record->state->is(ArticleStates::Published)) {
            $this->record->update(['publisher_id' => auth()->id(), 'published_at' => now()]);
        }
    }

    /**
     * Configures and returns the wizard form steps for word creation.
     *
     * Creates a sequential form flow beginning with general word information,
     * followed by geographical and status details. The form interface uses
     * standardized icons and a consistent twelve-column layout structure.
     * Each step pulls its schema configuration from the FormSchema class
     * to maintain consistency across the application.
     *
     * Step structure:
     * - "General Information" step renders word details using a language icon
     * - "Region & Status" step displays location data using a map icon
     *
     * @return array<int, \Filament\Schemas\Components\Wizard\Step> The configured wizard steps
     */
    protected function getSteps(): array
    {
        return [
            Step::make(trans('Algemene informatie'))
                ->icon(Heroicon::OutlinedLanguage)
                ->columnSpanFull()
                ->schema([self::sectionConfiguration()->schema(ArticleForm::generalInformationComponent())]),

            Step::make(trans('Regio & status'))
                ->icon('heroicon-o-map')
                ->columnSpanFull()
                ->schema([self::sectionConfiguration()->schema(ArticleForm::regionInformationComponent())]),

            Step::make('Gerelateerde woorden')
                ->icon(Heroicon::OutlinedLink)
                ->columnSpanFull()
                ->schema([Section::make()->schema(ArticleForm::getRelatedWordsRepeater())]),

            Step::make('Voorbeeldzinnen')
                ->icon(Heroicon::OutlinedChatBubbleLeftRight)
                ->columnSpanFull()
                ->schema(ArticleForm::exampleSentenceRepeater()),

            Step::make(trans('Bronnen'))
                ->icon(Heroicon::OutlinedBookOpen)
                ->columnSpanFull()
                ->schema([Section::make()->schema(ArticleForm::sourceRepeater())]),
        ];
    }

    /**
     * Creates a configured Section component for Filament forms.
     *
     * This method generates a Section component with predefined styling and layout settings.
     * It can be used to group related form fields together visually and logically.
     *
     * @param  string|null $sectionTitle  The title to display for the section (optional).
     * @return \Filament\Schemas\Components\Section The configured Section component.
     */
    public static function sectionConfiguration(?string $sectionTitle = null): Section
    {
        return Section::make($sectionTitle)
            ->compact()
            ->columns(12);
    }
}
