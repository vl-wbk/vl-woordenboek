<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ArticleStates;
use App\Models\User;
use App\Models\Article;
use App\UserTypes;

final readonly class ArticlePolicy
{
    public function update(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::New, ArticleStates::Draft, ArticleStates::Archived])
            && $user->user_type->notIn(enums: [UserTypes::Normal]);
    }

    public function sendForApproval(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::New, ArticleStates::Draft])
            && $user->user_type->notIn(enums: [UserTypes::Normal]);
    }

    public function rejectPublication(User $user, Article $article): bool
    {
        return $article->state->is(enum: ArticleStates::Approval)
            && $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::EditorInChief]);
    }

    public function publishArticle(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::Approval, ArticleStates::Archived])
            && $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::EditorInChief]);
    }

    public function archiveArticle(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::Published, ArticleStates::Approval])
            && $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::EditorInChief]);
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::EditorInChief])
            && $article->state->in(enums: [ArticleStates::New, ArticleStates::Draft]);
    }
}
