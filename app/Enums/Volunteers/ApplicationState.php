<?php

declare(strict_types=1);

namespace App\Enums\Volunteers;

use App\Models\VolunteerApplications;
use ArchTech\Enums\Comparable;
use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum ApplicationState: int implements HasColor, HasIcon, HasLabel
{
    use Comparable;
    
    case Open = 1;
    case Approved = 2; 
    case Rejected = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => 'Openstaand', 
            self::Approved => 'Goedgekeurd',
            self::Rejected => 'Afgewezen',
        };
    }

    public function getColor(): string 
    {
        return match ($this) {
            self::Open => 'info', 
            self::Approved => 'success', 
            self::Rejected => 'danger',
        };
    }

    public function getModalHeading(): string 
    {
        return match ($this) {
            self::Open => 'Openstaande aanmelding',
            self::Approved => 'Goedgekeurde aanmelding',
            self::Rejected => 'Afgewezen aanmelding',
        };
    }

    public function getModalDescription(VolunteerApplications $volunteerApplication): string 
    {
        return 'Algemene informatie over de aanmelding als vrijwilliger van een gebruiker';
    }

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::Open => Heroicon::OutlinedEllipsisHorizontalCircle, 
            self::Approved => Heroicon::OutlinedCheckBadge,
            self::Rejected => Heroicon::OutlinedXCircle,
        };
    }
}
