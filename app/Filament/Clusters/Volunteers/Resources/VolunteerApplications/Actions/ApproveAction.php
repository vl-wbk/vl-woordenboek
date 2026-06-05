<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Actions;

use App\Enums\Volunteers\ApplicationState;
use App\Models\VolunteerApplications;
use App\Notifications\VolunteerApprovalNotification;
use App\Policies\VolunteerApplicationsPolicy;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Throwable;

/**
 * @property \App\Models\VolunteerApplications $record
 */
final class ApproveAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): string
    {
        return "approve";
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label("Goedkeuren");
        $this->color("success");
        $this->authorize(VolunteerApplicationsPolicy::Approve);
        $this->icon(Heroicon::OutlinedCheckBadge);
        $this->requiresConfirmation();

        $this->modalHeading("Aanmelding goedkeuren");
        $this->modalDescription(
            fn(VolunteerApplications $volunteerApplication): string => $this->composeConfirmationMessage($volunteerApplication),
        );

        $this->successNotificationTitle("De aanmelding is goedgekeurd en behandeld.");
        $this->failureNotificationTitle("Helaas pindakaas! Er is iets fout gelopen.");

        $this->action(function (): void {
            if ($this->process(fn(): bool => $this->handleVolunteerRequestApproval())) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    /**
     * @throws Throwable when the database transaction couldn't complete successfully
     */
    private function handleVolunteerRequestApproval(): bool
    {
        $role = Role::findById($this->record->volunteerPosition->role_id);

        return DB::transaction(function () use ($role): bool {
            if (!$this->record->user->hasRole($role)) {
                $this->record->user->assignRole($role);
            }

            $this->record->user->update(["user_type" => $this->record->volunteerPosition->associated_user_group]);
            $this->record->update(["state" => ApplicationState::Approved]);

            Notification::sendNow($this->record->user, new VolunteerApprovalNotification());

            return true;
        });
    }

    private function composeConfirmationMessage(VolunteerApplications $volunteerApplications): string
    {
        return __(
            "Bij het goedkeuring van de aanmelding zal :user verplaatst worden naar de :usergroup gebruikersgroep en de :role permissiegroep toegewezen krijgen. Weet je zeker dat je dit wilt doen?",
            [
                "usergroup" =>
                    $volunteerApplications->volunteerPosition->associated_user_group->getLabel() ??
                    $volunteerApplications->user->user_type->getLabel(),
                "role" => Role::findById($volunteerApplications->volunteerPosition->role_id)->name,
                "user" => $volunteerApplications->user->name,
            ],
        );
    }
}
