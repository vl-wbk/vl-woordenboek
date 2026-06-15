<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Articles\Resources\CorrectionProposals\Pages;

use A909M\FilamentStateFusion\Actions\StateFusionAction;
use A909M\FilamentStateFusion\Actions\StateFusionActionGroup;
use App\Attributes\Todo;
use App\Concerns\HandlesDatabaseTransactions;
use App\Filament\Clusters\Articles\Resources\CorrectionProposals\CorrectionProposalResource;
use App\Models\CorrectionProposal;
use App\Policies\CorrectionProposalPolicy;
use App\States\Articles\Corrections\ApprovedState;
use App\States\Articles\Corrections\CorrectionState;
use App\States\Articles\Corrections\RejectedState;
use App\States\ExampleSentence\Approved;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use App\Models\CorrectionProposal;
use Override;

final class EditCorrectionProposal extends EditRecord
{
    use HandlesDatabaseTransactions;

    protected static string $resource = CorrectionProposalResource::class;

    #[Override]
    #[Todo('check if we can configure the redirect url from the state actions to the next pending proposal')]
    protected function getFormActions(): array
    {
        return [
            $this->getSubmitFormAction()->icon(Heroicon::OutlinedPaperAirplane),
            $this->getApproveFormAction(),
            $this->getRejectFormAction(),
            $this->getCancelFormAction(),
        ];
    }

    #[Override]
    public function getRecordTitle(): string|Htmlable
    {
        return "correctie: #{$this->getRecord()->id}";
    }

    private function getRejectFormAction(): StateFusionAction
    {
        return StateFusionAction::make('reject')
            ->label('Afwijzen')
            ->authorize(CorrectionProposalPolicy::Reject)
            ->modal()
            ->transitionTo(RejectedState::class)
            ->successRedirectUrl(CorrectionProposalResource::getUrl('index'))
            ->after(function (CorrectionProposal $correctionProposal, array $data): void {
                if (! $data['exclude_reputation']) {
                    $this->executeInTransaction(
                        callback: fn () => $correctionProposal->author->subtractPoints(
                            points: 6,
                            reason: 'Afwijzing van een correctie')
                        );
                }
            });
    }

    private function getApproveFormAction(): StateFusionAction
    {
        return StateFusionAction::make('approve')
            ->label('Goedkeuren')
            ->authorize(CorrectionProposalPolicy::Approve)
            ->transitionTo(ApprovedState::class)
            ->successRedirectUrl(CorrectionProposalResource::getUrl('index'))
            ->after(function (CorrectionProposal $correctionProposal): void {
                $this->executeInTransaction(
                    callback: fn () => $correctionProposal->author->awardPoints(
                        points: 4,
                        reason: 'Goedkeuring van een correctie')
                    );
                });
    }
}
