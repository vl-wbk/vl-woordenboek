<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class Pending extends SentenceState
{
    public function getColor(): string
    {
        return 'gray';
    }

    public function getIcon(): BackedEnum
    {
        return Heroicon::ChatBubbleBottomCenterText;
    }

    public function getLabel(): string
    {
        return 'openstaande contributie';
    }
}
