<?php

declare(strict_types=1);

namespace App\States\Posts;

use App\Filament\Clusters\Blog\Resources\BlogResource\Enums\Status;
use App\Models\Blog;
use Illuminate\Support\Facades\DB;

class PublicationState implements PublicationStateContract
{
    public function __construct(
        public readonly Blog $blog,
    ) {
    }

    public function transitionToPublished(): bool
    {
        return DB::transaction(callback: fn (): bool => $this->blog->update(attributes: [
            'status' => Status::Published,
        ]));
    }

    public function transitionToDraft(): bool
    {
        return DB::transaction(callback: fn (): bool => $this->blog->update(attributes: [
            'status' => Status::Draft,
        ]));
    }
}
