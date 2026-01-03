<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Actions;

use App\Enums\VolunteerApplicationState;
use App\Models\VolunteerApplication;
use App\Policies\VolunteerApplicationPolicy;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms\Components\Select;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * @todo Document this class
 */
final class ApproveApplicationAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'approve-volunteer-application';
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Button customization
        $this->color(color: 'gray');
        $this->icon(icon: Heroicon::OutlinedCheck);
        $this->label(label: __('filament/resources/volunteer-applications.actions.approve.label'));
        $this->authorize(VolunteerApplicationPolicy::Approve);


        // Modal Configuration 
        $this->modalHeading(heading:  __('filament/resources/volunteer-applications.actions.approve.label'));
        $this->modalAlignment(Alignment::Center);

        $this->modalDescription(description:fn (VolunteerApplication $volunteerApplication): string =>  __('filament/resources/volunteer-applications.actions.approve.modal.description', [
            'role' => $volunteerApplication->role->getLabel()
        ]));
        
        $this->modalIcon(icon: Heroicon::OutlinedCheck);
        $this->modalIconColor('success');
        $this->modalWidth(Width::Medium);
        $this->modalFooterActionsAlignment(Alignment::Center);
        $this->modalSubmitActionLabel(label: __('filament/resources/volunteer-applications.actions.approve.modal.submit-label'));
        $this->modalCancelAction(false);

        // Noàtifications 
        $this->successNotificationTitle(fn (VolunteerApplication $volunteerApplication): string => __('filament/resources/volunteer-applications.actions.approve.notifications.success', [
            'role' => $volunteerApplication->role->getLabel(), 'user' => $volunteerApplication->user->name
        ]));

        $this->failureNotificationTitle('Helaas pindakaas! Er is iets misgelopen');

        // Form & handling
        $this->schema(schema: $this->getFormSchema());

        $this->action(function (array $data, VolunteerApplication $volunteerApplication): void {
            if ($this->approveVolunteerApplication($volunteerApplication, $data)) {
                $this->success();
                return;
            }

            $this->failure();
        });
    }

    private function approveVolunteerApplication(VolunteerApplication $volunteerApplication, array $data): bool
    {
        return DB::transaction(function () use ($volunteerApplication, $data): bool {
            $volunteerApplication->update(['closed_at' => now(), 'state' => VolunteerApplicationState::Approved]);
            $volunteerApplication->reviewer()->associate(auth()->user())->save();
            $volunteerApplication->user->roles()->sync($data['roles']);

            return true;
        });
    }

    private function getFormSchema(): array 
    {
        return [
            Select::make('roles')
                ->label(label: __('filament/resources/volunteer-applications.actions.approve.modal.permission-select'))
                ->options(Role::query()->pluck('name', 'id'))
                ->formatStateUsing(fn ($record): array => $record->user?->roles->pluck('id')->toArray())
                ->required()
                ->multiple()
                ->searchable(false)
                ->native(false),
        ];
    }
}