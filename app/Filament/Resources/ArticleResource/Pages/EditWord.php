<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStates;
use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use Kenepa\ResourceLock\Resources\Pages\Concerns\UsesResourceLock;
use App\Filament\Resources\ArticleResource\Schema\FormSchema;
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
     * Configures available actions in the page header.
     * Currently provides a delete action with a trash icon, allowing administrators to remove articles when necessary.
     * The action is positioned in the header for consistent placement and easy access.
     *
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
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
        ];
    }

    public function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->state === ArticleStates::New || $this->record->state === ArticleStates::Archived) {
            $data['state'] = ArticleStates::Draft;
            $data['editor_id'] = auth()->id();
        }

        return $data;
    }
}
