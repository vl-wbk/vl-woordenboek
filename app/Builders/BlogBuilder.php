<?php

declare(strict_types=1);

namespace App\Builders;

use Illuminate\Database\Eloquent\Builder;

final class BlogBuilder extends Builder
{
    public function hasCommentsEnabled(): bool
    {
        return $this->model->comments_enabled;
    }

    public function hasCommentsDisabled(): bool
    {
        return ! $this->hasCommentsEnabled();
    }
}
