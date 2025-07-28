<?php

declare(strict_types=1);

namespace App\Actions\Comments;

use App\Models\Comment;
use Illuminate\Support\Facades\DB;

final readonly class DeleteReaction
{
    public function handle(Comment $comment): bool
    {
        return DB::transaction(callback: fn () => $comment->delete());
    }
}
