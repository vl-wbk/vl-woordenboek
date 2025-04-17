<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStates;
use App\Filament\Resources\ArticleResource;
use App\Filament\Resources\ArticleResource\Actions\RemoveEditorAction;
use App\Filament\Resources\ArticleResource\Actions\States\PublishArticleAction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use Kenepa\ResourceLock\Resources\Pages\Concerns\UsesResourceLock;
use App\Filament\Resources\ArticleResource\Schema\FormSchema;
use App\States\Articles\ArticleState;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;

/**
 * EditWord provides a wizard-based interface for editing dictionary articles in the Vlaams Woordenboek.
 *
 * This page component implements a multi-step form for editing dictionary entries, with built-in resource locking to prevent concurrent edits.
 * It manages the complete editing workflow including state transitions and form validation across multiple steps.
 * The interface guides editors through a structured process to ensure consistent and complete article updates.
 *
 * @property \App\Models\Article $record The dictioniry article entity from the database
 *
 * @package App\Filament\Resources\ArticleResource\Pages
 */
final class EditWord extends EditRecord
{
    use UsesResourceLock;
    use HasWizard;

    /**
     * The resource class this page component belongs to, establishing the connection between this editing interface and the ArticleResource management system.
     * This relationship enables proper routing and resource handling throughout the application.
     *
     * @var string
     */
    protected static string $resource = ArticleResource::class;

    /**
     * Configures header actions for the editing page.
     *
     * The header actions are displayed at the top of the page and provide quick access to common operations.
     * In this case, the actions include:
     *
     * - RemoveEditorAction: Enables the removal of the assigned editor from the article.
     * - DeleteAction: Allows the article to be deleted using an icon styled as a trash can.
     *
     * This modular configuration ensures that these actions are prominent and easily accessible,
     * while also allowing future developers to add or modify actions as requirements evolve.
     *
     * @return array<int, Actions\Action> The set of actions displayed in the header.
     */
    protected function getHeaderActions(): array
    {
        return [
            RemoveEditorAction::make(),
            PublishArticleAction::make(),
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

    /**
     * Constructs the form interface using a wizard component for a guided editing experience.
     * The wizard provides intuitive navigation between steps, with cancel and submit actions clearly presented.
     * The interface supports optional step skipping and uses a full-width layout for optimal content presentation.
     * The form inherits base functionality while adding specialized behavior for dictionary article editing.
     *
     * @param  Form $form  The Filament form instance that needs to be configured.
     * @return Form        The configured Filament form instance.
     */
    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false)
            ])->columns(null);
    }

    /**
     * Defines the structure and content of the wizard steps used in article editing.
     * The first step focuses on general information with language settings, while the second step handles geographic coverage and publication status.
     * Each step utilizes dedicated schema configurations from FormSchema to maintain consistency and facilitate future modifications to the form structure.
     *
     * @return array<int, Wizard\Step>
     */
    protected function getSteps(): array
    {
        return [
            Wizard\Step::make(trans('Algemene informatie'))
                ->icon('heroicon-o-language')
                ->columns(12)
                ->schema([FormSchema::sectionConfiguration()->schema(FormSchema::getDetailSchema())]),
            Wizard\Step::make(trans('Regio & status'))
                ->icon('heroicon-o-map')
                ->columns(12)
                ->schema([FormSchema::sectionConfiguration()->schema(FormSchema::getStatusAndRegionDetails())]),
            Wizard\Step::make(trans('Bronnen'))
                ->icon('heroicon-o-book-open')
                ->columns('12')
                ->schema([FormSchema::sectionConfiguration()->schema(FormSchema::getSourceSchema())])
        ];
    }

    /**
     * Modifies the form data before saving it to the database.
     *
     * When the form is submitted, this method is invoked to perform any necessary pre-save adjustments.
     * In this implementation, the method checks the current state of the article and, if the article's state is 'New', it initiates a transition to the 'Editing' state.
     * This transition is handled by the article's state management system  (through the articleStatus() method), which encapsulates the business rules associated with state changes.
     * After performing the state transition, the method returns the form data array unaltered, allowing the save operation to proceed.
     *
     * Future developers should note that this hook provides a convenient point to inject additional data modifications or  side effects required before the article is persisted.
     * Any further adjustments to the article lifecycle can be added within this method.
     *
     * @param  array<string, string>  $data  The form data to be saved.
     * @return array<string, string>         The (possibly modified) form data.
     */
    public function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->state->in(enums: [ArticleStates::New, ArticleStates::ExternalData]) && $this->record->editor()->doesntExist()) {
            $this->record->articleStatus()->transitionToEditing();
        }

        return $data;
    }
}
