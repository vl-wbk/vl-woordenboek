<?php

namespace App\Models;

use BeyondCode\Comments\Comment as BaseCommentEntity;
use Overtrue\LaravelLike\Traits\Likeable;

/**
 * The Comment model extends the base comment entity from the BeyondCode\Comments package.
 *
 * This model represents user comments within the application, leveraging the robust features provided by the `beyondcode/laravel-comments` package for comment management.
 * Additionally, it integrates the `overtrue/laravel-like` package to enable users to like comments.
 *
 * All core comment-related properties (e.g., body, author, commentable_type, commentable_id) and relationships are inherited from the `BeyondCode\Comments\Comment` base class.
 *
 * @property User  $commentator The authenticated user who created the comment.
 * @property mixed $commentable The resource entity that is attached to the comment.
 *
 * @method hasCommentsEnabled()     Indicates that the comments are enabled on the child resource.
 * @method hasCommentsDisabled()    Indicates that the comments are disabled on the child resource.
 * @method isPublished()            INdicates that the comment has been published in the application.
 *
 * @package App\Models
 */
final class Comment extends BaseCommentEntity
{
    use Likeable;
}
