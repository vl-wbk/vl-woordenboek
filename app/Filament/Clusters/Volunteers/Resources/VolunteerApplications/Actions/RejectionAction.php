<?php 

declare(strict_types=1); 

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Actions;

use App\Enums\Volunteers\ApplicationState;
use App\Policies\VolunteerApplicationsPolicy;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

final class RejectionAction extends Action
{
    use CanCustomizeProcess; 

    public static function getDefaultName(): ?string
    {
        return 'reject';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Afwijzen'); 
        $this->color('danger');
        $this->outlined(); 
        $this->authorize(VolunteerApplicationsPolicy::Approve);
        $this->icon(Heroicon::OutlinedXCircle);
        $this->requiresConfirmation();

        // Modal settings 
        $this->modalHeading('Aanmelding afwijzen');
        $this->modalDescription('U staat op het punt om een aanmelding af te wijzen! Weet je zeker dat je dit wilt?');

        $this->successNotificationTitle('De aanmelding is afgewezen en behandeld.');
        $this->failureNotificationTitle('Helaas pindakaas! Er is iets fout gelopen.');

        $this->action(function (): void {
            if ($this->process(fn (): bool => $this->handleVolunteerRequestRejection())) {
                $this->success(); 
                return;
            }

            $this->failure();
        });
    }

    private function handleVolunteerRequestRejection(): bool 
    {
        return DB::transaction(function (): bool {
            return $this->record->update(attributes: ['state' => ApplicationState::Rejected]);
        });
    }
}