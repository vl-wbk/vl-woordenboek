<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

final class CategoryBuilder extends Builder
{
    public function guestCategories(): self
    {
        return $this->where('internal', false);
    }
}
