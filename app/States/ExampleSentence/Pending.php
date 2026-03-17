<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class Pending extends SentenceState
{
    public function getColor(): string|array|null
    {
        return 'gray';
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return Heroicon::ChatBubbleBottomCenterText;
    }

    public function getLabel(): string|Htmlable|null
    {
        return 'openstaande contributie';
    }

    public function getDescription(): string|Htmlable|null
    {
        return null;
    }
}
