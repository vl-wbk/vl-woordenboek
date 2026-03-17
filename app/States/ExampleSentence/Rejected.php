<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class Rejected extends SentenceState
{
    public function getColor(): string|array|null
    {
        return 'danger';
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedXMark;
    }

    public function getLabel(): string|Htmlable|null
    {
        return 'Afgewezen';
    }

    public function getDescription(): string|Htmlable|null
    {
        return null;
    }
}
