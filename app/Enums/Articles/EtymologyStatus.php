<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use ArchTech\Enums\Comparable;
use Filament\Support\Contracts\{HasLabel, HasIcon, HasColor, HasDescription};

/**
 * @todo Document enumeration
 */
enum EtymologyStatus: int implements HasColor, HasDescription, HasIcon, HasLabel
{
    use Comparable;

    case Draft = 1;
    case UnderReview = 2;
    case Rejected = 3;
    case Published = 4;
    case Archived = 5;

    public function frontendBadge(): string
    {
        return match ($this) {
            self::Draft => 'badge-warning',
            self::UnderReview => 'badge-primary',
            self::Rejected => 'badge-danger',
            self::Published => 'badge-success',
            self::Archived => 'badge-gray',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Draft => 'Klad ontwerp',
            self::UnderReview => 'In review',
            self::Rejected => 'Afgewezen',
            self::Published => 'Gepubliceerd',
            self::Archived => 'Gearchiveerd',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::Draft => 'De etymologische data wordt nog verder uitgewerkt of is onvolledig.',
            self::UnderReview => 'De etymologische data is ingediend voor een redactionele beoordeling',
            self::Rejected => 'De etymologische data is nagekeken maar expliciet geweigerd als bijdrage',
            self::Published => 'De etymologische data is publiek beschikbaar',
            self::Archived => 'De Etymologische data word niet meer weergegeven, maar wordt nog bewaard voor naslag.',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Draft => 'heroicon-o-pencil-square',
            self::UnderReview => 'heroicon-o-paper-airplane',
            self::Rejected => 'heroicon-o-x-circle',
            self::Published => 'heroicon-o-check',
            self::Archived => 'heroicon-o-archive-box',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Draft => 'warning',
            self::UnderReview => 'info',
            self::Rejected, self::Archived => 'danger',
            self::Published => 'success',
        };
    }

    public function isRejected(): bool
    {
        return $this->is(self::Rejected);
    }

    public function isPublished(): bool
    {
        return $this->is(self::Published);
    }

    public function isArchived(): bool
    {
        return $this->is(self::Archived);
    }
}
