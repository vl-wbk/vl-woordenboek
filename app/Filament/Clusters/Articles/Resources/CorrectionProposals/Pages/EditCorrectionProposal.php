<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Pages;

use A909M\FilamentStateFusion\Actions\StateFusionAction;
use A909M\FilamentStateFusion\Actions\StateFusionActionGroup;
use App\Attributes\Todo;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\CorrectionProposalResource;
use App\Policies\CorrectionProposalPolicy;
use App\States\Articles\Corrections\ApprovedState;
use App\States\Articles\Corrections\CorrectionState;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Override;

final class EditCorrectionProposal extends EditRecord
{
    protected static string $resource = CorrectionProposalResource::class;

    #[Override]
    #[Todo('check if we can configure the redirect url from the state actions to the next pending proposal')]
    protected function getFormActions(): array
    {
        return [
            $this->getSubmitFormAction()
                ->icon(Heroicon::OutlinedPaperAirplane),

            StateFusionAction::make('approve')
                ->label('Goedkeuren')   
                ->authorize(CorrectionProposalPolicy::Approve) 
                ->transitionTo(ApprovedState::class)
                ->successRedirectUrl(CorrectionProposalResource::getUrl('index')),

            $this->getCancelFormAction(),
        ];
    }

    #[Override]
    public function getRecordTitle(): string|Htmlable
    {
        return "correctie: #{$this->getRecord()->id}";
    }
}
