<?php

declare(strict_types=1);

namespace App\Actions\Comments;

use App\Http\Requests\Comments\StoreCommentRequest;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final readonly class StoreReaction
{
    public function handle(Blog $blog, StoreCommentRequest $storeCommentRequest): Comment|Model
    {
        return DB::transaction(
            callback: fn (): Comment|Model => $blog->comment($storeCommentRequest->string('reactie')->toString())
        );
    }
}
