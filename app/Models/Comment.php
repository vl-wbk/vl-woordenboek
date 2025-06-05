<?php

namespace App\Models;

use BeyondCode\Comments\Comment as BaseCommentEntity;
use Overtrue\LaravelLike\Traits\Likeable;

final class Comment extends BaseCommentEntity
{
    use Likeable;
}
