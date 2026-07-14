<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Volunteers\Resources\VolunteerApplications\Actions;

use App\Models\VolunteerApplications;
use BackedEnum;
use Filament\Actions\ViewAction as ActionsViewAction;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;

final class ViewAction extends ActionsViewAction
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Aanmelding bekijken');

        $this->modalIcon(fn (VolunteerApplications $volunteerApplications): BackedEnum => $volunteerApplications->state->getIcon());
        $this->modalIconColor(fn (VolunteerApplications $volunteerApplications): string => $volunteerApplications->state->getColor());
        $this->modalHeading(fn (VolunteerApplications $volunteerApplications): string => $volunteerApplications->state->getModalHeading());
        $this->modalDescription(fn (VolunteerApplications $volunteerApplications): string => $volunteerApplications->state->getModalDescription($volunteerApplications));

        $this->modalWidth(Width::SevenExtraLarge);
        $this->modalCancelAction(false);

        $this->extraModalFooterActions([
            ApproveAction::make(),
            RejectionAction::make(),
        ]);
    }
}
