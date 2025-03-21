<?php

declare(strict_types=1);

namespace App\Builders;

use App\Enums\Visibility;
use Illuminate\Database\Eloquent\Builder;

final class ArticlesBuilder extends Builder
{
    public function __construct($query)
    {
        parent::__construct($query);
    }

    public function published(): Builder
    {
        return $this->where('visibility', Visibility::Published);
    }

    public function setVisibility(Visibility $visibility): self
    {
        $this->update(['visibility' => $visibility]);

        return $this;
    }
}
