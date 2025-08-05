<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Disclaimer;
use App\Models\User;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

final readonly class DisclaimerPolicy
{
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Articles')) {
            return Response::deny(
                message: __('authorization.policies.responses.deny_before_message', replace: [
                    'resource' => __('authorization.resources.disclaimers'),
                ]),
            );
        }

        return null;
    }

    public function viewAny(User $user): Response
    {
        if ($user->can('view_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_view_any_message', replace: [
                'resource' => __('authorization.resources.disclaimers'),
            ]),
        );
    }

    public function view(User $user, Disclaimer $disclaimer): Response
    {
        if ($user->can('view_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_create_message', replace: [
                'resource' => __('authorization.resources.disclaimer'),
            ]),
        );
    }

    public function create(User $user): Response
    {
        if ($user->can('create_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_create_message', replace: [
                __('authorization.resources.disclaimer'),
            ]),
        );
    }

    public function update(User $user): Response
    {
        if ($user->can('update_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_update_message', replace: [
                'resource' => __('authorization.resources.disclaimer'),
            ]),
        );
    }

    public function delete(User $user): Response
    {
        if ($user->can('delete_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_delete_message', replace: [
                'resource' => __('authorization.resources.disclaimer'),
            ]),
        );
    }

    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_delete_any_message', replace: [
                'resource' => __('authorization.resources.disclaimers'),
            ]),
        );
    }
}
