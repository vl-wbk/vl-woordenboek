<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum DisclaimerTypes: int implements HasIcon, HasLabel
{
    case Default = 0;
    case Warning = 1;
    case Danger = 2;

    public function getIcon(): string
    {
        return match($this) {
            self::Default => 'heroicon-s-information-circle',
            self::Warning => 'heroicon-s-exclamation-triangle',
            self::Danger => 'heroicon-s-hand-raised',
        };
    }

    public function getLabel(): string
    {
        return match($this) {
            self::Default => 'Standaard disclaimer',
            self::Warning => 'Waarschuwings disclaimer',
            self::Danger => 'Gevaren disclaimer',
        };
    }

    public function getFrontendAlertClass(): string
    {
        return match($this) {
            self::Default => 'alert alert-info',
            self::Warning => 'alert alert-warning',
            self::Danger => 'alert alert-danger',
        };
    }
}
