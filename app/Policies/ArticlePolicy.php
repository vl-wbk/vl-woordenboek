<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ArticleStates;
use App\Models\User;
use App\Models\Article;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

/**
 * @todo Because articles are a core functionality in our platform we should document the policy classes
 */
final readonly class ArticlePolicy
{
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Articles')) {
            return Response::deny(
                message: __('authorization.policies.responses.deny_before_message', replace: [
                    'resource' => __('authorization.resources.articles'),
                ]),
            );
        }

        return null;
    }

    public function update(User $user, Article $article): Response
    {
        $isPublishedOrAwaitinApproval = ($article->isPublished() || $article->state->is(ArticleStates::Approval));

        if ($isPublishedOrAwaitinApproval && $user->can('update_article')) {
            return Response::deny(
                message: __('authorization.policies.responses.deny_update_message', replace: [
                    'resource' => __('authorization.resources.article'),
                ]),
            );
        }

        if ($article->state->in(enums: [ArticleStates::New, ArticleStates::ExternalData, ArticleStates::Draft, ArticleStates::Archived]) && $user->can('update_article')) {
            return Response::deny(
                message: __('authorization.policies.responses.deny_update_message', replace: [
                    'resource' => __('authorization.resources.article'),
                ]),
            );
        }

        return Response::allow();
    }

    public function sendForApproval(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::Draft])
            && $user->can('send_for_approval_article');
    }

    public function publish(User $user, Article $article): bool
    {
        if ($article->state->isNot(enum: ArticleStates::Approval)) {
            return false;
        }

        if ($user->cannot('publish_article')) {
            return false;
        }

        return $article->editor()->exists() && $article->editor()->isNot($user);
    }

    public function unpublish(User $user, Article $article): bool
    {
        return $article->isPublished() && $user->can('unpublish_article');
    }

    public function detachEditor(User $user, Article $article): bool
    {
        if ($article->state->isNot(enum: ArticleStates::Draft)) {
            return false;
        }

        if ($article->editor()->is($user)) {
            return true;
        }

        return $user->can('detach_disclaimer_article');
    }

    public function attachDisclaimer(User $user, Article $article): bool
    {
        return $article->disclaimer()->doesntExist() && $user->can('attach_disclaimer_article');
    }

    public function detachDisclaimer(User $user, Article $article): bool
    {
        return $article->disclaimer()->exists() && $user->can('detach_disclaimer_article');
    }

    public function archiveArticle(User $user, Article $article): bool
    {
        return $article->state->in(enums: [ArticleStates::Published, ArticleStates::Approval])
            && $user->can('archive_article');
    }

    public function unarchive(User $user, Article $article): bool
    {
        return $article->state->is(ArticleStates::Archived) && $user->can('unarchive_article');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->can('delete_article')
            && $article->state->in(enums: [ArticleStates::New, ArticleStates::Draft, ArticleStates::ExternalData, ArticleStates::Archived]);
    }

    public function restore(User $user): bool
    {
        return $user->can('restore_article');
    }

    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_article');
    }

    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_article');
    }
}
