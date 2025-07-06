<?php

declare(strict_types=1);

namespace App\Builders;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use Illuminate\Database\Eloquent\Builder;

/**
 * @todo Document clpass
 * @template-extends Builder<\App\Models\Blog>
 */
final class BlogBuilder extends Builder
{
    public function hasCommentsEnabled(): bool
    {
        return $this->model->comments_enabled;
    }

    public function isPublished(): bool
    {
        return $this->model->status->is(Status::Published);
    }

    public function hasCommentsDisabled(): bool
    {
        return ! $this->hasCommentsEnabled();
    }
}
