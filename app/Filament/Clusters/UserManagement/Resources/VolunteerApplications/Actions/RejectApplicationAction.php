<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Actions;

use App\Enums\VolunteerApplicationState;
use App\Models\VolunteerApplication;
use App\Policies\VolunteerApplicationPolicy;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;

/**
 * @todo Document this class
 */
final class RejectApplicationAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): string
    {
        return 'reject-volunteer-application';
    }

    protected function setUp(): void 
    {
        parent::setUp();

        // Button customization 
        $this->color('gray');
        $this->icon(Heroicon::OutlinedXMark);
        $this->label(label: __('filament/resources/volunteer-applications.actions.reject.label'));
        $this->authorize(VolunteerApplicationPolicy::Reject);

        // Modal configuration 
        $this->modalHeading(heading:  __('filament/resources/volunteer-applications.actions.reject.modal.heading'));
        $this->modalAlignment(Alignment::Center);

        $this->modalDescription(description: fn (VolunteerApplication $volunteerApplication): string => __('filament/resources/volunteer-applications.actions.reject.modal.description', [
            'user' => $volunteerApplication->user->name, 'role' => $volunteerApplication->role->getLabel(),
        ]));

        $this->modalIcon(icon: Heroicon::OutlinedXMark); 
        $this->modalIconColor('danger'); 
        $this->modalWidth(Width::Medium);
        $this->modalFooterActionsAlignment(Alignment::Center);
        $this->modalSubmitActionLabel(label: __('filament/resources/volunteer-applications.actions.reject.modal.submit-label'));
        $this->modalCancelAction(false);

        // Notifications 
        $this->successNotificationTitle(fn (VolunteerApplication $volunteerApplication): string => __('filament/resources/volunteer-applications.actions.reject.notifications.success', [
            'role' => $volunteerApplication->role->getLabel(), 'user' => $volunteerApplication->user->name
        ]));

        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen');

        // Form & handling
        $this->schema(schema: $this->getFormSchema());
        $this->action(function (array $data, VolunteerApplication $volunteerApplication): void {
            if ($this->rejectVolunteerApplication($volunteerApplication, $data)) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    private function rejectVolunteerApplication(VolunteerApplication $volunteerApplication, array $data): bool
    {
        return DB::transaction(function () use ($volunteerApplication, $data): bool {
            $volunteerApplication->fill(['closed_at' => now(), 'state' => VolunteerApplicationState::Rejected, 'rejection_reason' => $data['rejection_reason']]);
            $volunteerApplication->reviewer()->associate(auth()->user());
            $volunteerApplication->save();

            return true;
        });
    }

    private function getFormSchema(): array 
    {
        return [
            Textarea::make('rejection_reason')
                ->label(label: __('filament/resources/volunteer-applications.actions.reject.modal.rejection-reason'))
                ->required()
                ->placeholder('Beschrijf kort waarom de gebruiker word afgewezen')
                ->rows(5)
                ->autofocus()
        ];
    }
}