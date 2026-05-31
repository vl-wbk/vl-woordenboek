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
use Override;

final class ApprovedState extends CorrectionState implements HasColor, HasIcon, HasLabel, HasDescription
{
    #[Override]
    public function getLabel(): string
    {
        return __('Goedgekeurd');
    }

    #[Override]
    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedCheckCircle;
    }

    #[Override]
    public function getColor(): string|array|null
    {
        return 'success';
    }

    #[Override]
    public function getDescription(): string|Htmlable|null
    {
        return null;
    }
}