<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\UserExample;
use App\UserTypes;
use Illuminate\Auth\Access\Response;

final readonly class UserExamplePolicy
{
    public const string changeState = 'change-state';
    public const string changeStateAny = 'change-state-any';

    public function deleteAny(User $user): Response
    {
        if ($user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function changeState(User $user, UserExample $userExample): Response
    {
        if ($user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])) {
            return Response::allow();
        }

        return Response::deny();
    }

    public function changeStateAny(User $user): Response
    {
        if ($user->user_type->in(enums: [UserTypes::Developer, UserTypes::Administrators, UserTypes::EditorInChief])) {
            return Response::allow();
        }

        return Response::deny();
    }
}
