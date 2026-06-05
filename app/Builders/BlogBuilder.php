<?php

declare(strict_types=1);

namespace App\Builders;

use App\Attributes\Todo;
use App\Models\Blog;
use App\Filament\Clusters\Blog\Resources\Blogs\Enums\Status;
use Illuminate\Database\Eloquent\Builder;

/**s
 * @template-extends Builder<Blog>
 */
#[Todo(message: 'Provide docblocks for this class and methods', author: 'Tjoosten', priority: 'high', tags: ['documentation'])]
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
        return ! $this->model->hasCommentsEnabled();
    }
}
