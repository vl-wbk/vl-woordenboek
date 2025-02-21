<?php

declare(strict_types=1);

namespace App\Filament\Resources\WordResource\Pages;

use App\Filament\Resources\WordResource;
use App\Filament\Resources\WordResource\Schema\FormSchema;
use App\Models\Definition;
use App\Models\Word;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final class CreateWord extends CreateRecord
{
    use HasWizard;

    protected static string $resource = WordResource::class;

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
            Wizard\Step::make(trans('Definities'))
                ->icon('heroicon-o-queue-list')
                ->schema([Section::make()->compact()->schema(FormSchema::getDefinitionRepeater())]),
        ];
    }
}
