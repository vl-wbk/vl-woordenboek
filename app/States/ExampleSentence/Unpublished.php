<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class Unpublished extends SentenceState
{
    public function getColor(): string|array|null
    {
        return 'warning';
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedEyeSlash;
    }

    public function getLabel(): string|Htmlable|null
    {
        return 'Offline gehaald';
    }

    public function getDescription(): string|Htmlable|null
    {
        return null;
    }
}
