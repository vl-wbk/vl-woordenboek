<?php

declare(strict_types=1);

namespace App\Enums\Articles;

use ArchTech\Enums\Comparable;
use ArchTech\Enums\Meta\Meta;
use ArchTech\Enums\Metadata;
use App\Enums\MetaProperties\{Description, Label, Icon, Color};
use Filament\Support\Contracts\{HasLabel, HasIcon, HasColor, HasDescription};

#[Meta(Description::class, Label::class, Icon::class, Color::class)]
enum EtymologyStatus: int implements HasColor, HasDescription, HasIcon, HasLabel
{
    use Metadata;
    use Comparable;

    #[Label('Klad ontwerp')]
    #[Description('De etymolgische data wordt nog verder uitgewerkt of is onvolledig')]
    #[Icon('heoricon-o-pencil-square')]
    #[Color('warning')]
    case Draft = 1;

    #[Label('In review')]
    #[Description('De etymologische data is ingediend voor een redactionele beoordeling')]
    #[Icon('heroicon-o-paper-airplane')]
    #[Color('info')]
    case UnderReview = 2;

    #[Label('Afgewezen')]
    #[Description('De Etymoligsche data is nagekeken maar expliciet geweigerd als bijdrage')]
    #[Icon('heroicon-o-x-circle')]
    #[Color('danger')]
    case Rejected = 3;

    #[Label('Gepubliceerd')]
    #[Description('De etymologische data is publiek beschrikbaar')]
    #[Icon('heroicon-o-check')]
    #[Color('success')]
    case Published = 4;

    #[Label('Gearchiveerd')]
    #[Description('De Etymologische data word niet meer weergegeven, maar wordt nog bewaard voor naslag')]
    #[Icon('heroicon-o-archive-box')]
    #[Color('danger')]
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
        return self::label();
    }

    public function getDescription(): string
    {
        return self::description();
    }

    public function getIcon(): string
    {
        return self::icon();
    }

    public function getColor(): string
    {
        return self::color();
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
