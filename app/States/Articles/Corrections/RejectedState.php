<?php

declare(strict_types=1);

namespace App\States\Articles\Corrections;

use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasDescription;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class RejectedState extends CorrectionState implements HasColor, HasIcon, HasLabel, HasDescription
{
    public function getLabel(): string
    {
        return __('Afgewezen');
    }

    public function getIcon(): BackedEnum
    {
        return Heroicon::OutlinedXCircle;
    }

    public function getColor(): string|array|null
    {
        return 'danger';
    }

    public function getDescription(): string|Htmlable|null
    {
        return null;
    }
}
