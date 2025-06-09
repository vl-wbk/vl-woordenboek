<?php

declare(strict_types=1);

namespace App\Filament\Clusters\Blog\Resources\BlogResource\Enums;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

/**
 * @todo #249 Docblocks toevoegen aan de status enumeratie voor de blog posts
 */
enum Status: int implements HasLabel, HasColor, HasIcon
{
    use Comparable;

    case Draft = 0;
    case Published = 1;

    public function getLabel(): string
    {
        $label = match($this) {
            self::Draft => 'Klad versie',
            self::Published => 'Gepubliceerd',
        };

        return trans($label);
    }

    public function getColor(): string
    {
        return match($this) {
            self::Draft => 'warning',
            self::Published => 'success',
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::Draft => 'heroicon-o-pencil-square',
            self::Published => 'heroicon-o-globe-europe-africa',
        };
    }

    public function isPublished(): bool
    {
        return $this->is(self::Published);
    }

    public function isDraft(): bool
    {
        return $this->is(self::Draft);
    }
}
