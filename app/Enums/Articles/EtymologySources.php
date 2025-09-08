<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use Filament\Support\Contracts\HasLabel;

enum EtymologySources: int implements HasLabel
{
    case EtymologieBank = 1;
    case WNT = 2;
    case Other = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::EtymologieBank => __('Etymologiebank'),
            self::WNT => __('WNT'),
            self::Other => __('Andere'),
        };
    }
}
