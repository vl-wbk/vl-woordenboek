<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;

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
