<?php

declare(strict_types=1);

namespace App\Filament\Clusters\UserManagement\Resources\VolunteerApplications\Actions;

use App\Policies\VolunteerApplicationPolicy;
use Filament\Actions\Action;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;

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

        // Notifications 

        // Form & handling
    }
}