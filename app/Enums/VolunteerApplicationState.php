<?php 

declare(strict_types=1);

namespace App\Enums;

use ArchTech\Enums\Comparable;
use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

enum VolunteerApplicationState: int implements HasLabel, HasColor, HasIcon 
{
    use Comparable;
    
    case Open = 1; 
    case Approved = 2;
    case Rejected = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => __('Onbehandeld'), 
            self::Approved => __('Geaccepteerd'),
            self::Rejected => __('Afgewezen'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Open => 'warning', 
            self::Approved => 'success', 
            self::Rejected => 'danger',
        };
    }

    public function getIcon(): BackedEnum
    {
        return match ($this) {
            self::Open => Heroicon::OutlinedEllipsisHorizontalCircle,
            self::Approved => Heroicon::OutlinedCheckBadge,
            self::Rejected => Heroicon::OutlinedExclamationCircle,
        };
    }
}