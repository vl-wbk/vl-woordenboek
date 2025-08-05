<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;
use Cog\Laravel\Ban\Models\Ban;
use Illuminate\Auth\Access\Response;

final readonly class BanPolicy
{
    public function before(User $user, string $ability): ?Response
    {
        if ($user->can('page_UserManagement')) {
            return null;
        }

        return Response::denyAsNotFound(
            message: __('authorization.policies.responses.deny_before_message', replace: [
                'resource' => __('authorization.resources.bans'),
            ]),
        );
    }

    public function viewAny(User $user): Response
    {
        if ($user->can('view_any_ban')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_view_any_message', replace: [
                'resource' => __('authorization.resources.bans'),
            ]),
        );
    }

    public function view(User $user, Ban $ban): Response
    {
        if ($user->can('view_ban')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorisation.policies.responses.deny_view_message', replace: [
                'resource' => __('authorisation.resources.ban'),
            ]),
        );
    }

    public function update(User $user, Ban $ban): Response
    {
        if ($user->can('update_ban')) {
            response::allow();
        }

        return Response::deny(
            message: __('authorisation.policies.responses.deny_update_message', replace: [
                'resource' => __('authorisation.resources.ban'),
            ]),
        );
    }
    public function delete(User $user, Ban $ban): Response
    {
        if ($user->can('delete_ban')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorisation.policies.responses.deny_delete_message', replace: [
                'resource' => __('auhtorisation.resources.ban'),
            ]),
        );
    }
}
