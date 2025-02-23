<?php

declare(strict_types=1);

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Enums\ArticleStates;
use App\Filament\Resources\ArticleResource;
use App\Models\Article;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\EditRecord\Concerns\HasWizard;
use Kenepa\ResourceLock\Resources\Pages\Concerns\UsesResourceLock;
use App\Filament\Resources\ArticleResource\Schema\FormSchema;
use Filament\Forms\Form;
use Filament\Forms\Components\Wizard;

final class EditWord extends EditRecord
{
    use UsesResourceLock;
    use HasWizard;

    protected static string $resource = ArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }

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
     * @return Step[]
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
