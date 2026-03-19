<?php

declare(strict_types=1);

namespace App\States\ExampleSentence;

use BackedEnum;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;

final class Approved extends SentenceState
{
    public function getColor(): string|array|null
    {
        return 'success';
    }

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return Heroicon::OutlinedDocumentCheck;
    }

    public function getLabel(): string|Htmlable|null
    {
        return 'gepubliceerd';
    }

    public function getDescription(): string|Htmlable|null
    {
        return null;
    }
}
