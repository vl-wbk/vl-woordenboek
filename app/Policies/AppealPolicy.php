<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Appeal;
use App\Models\User;
use App\UserTypes;
use Illuminate\Auth\Access\Response;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

final readonly class AppealPolicy
{
    public function viewAny(User $user): Response
    {
        if ($user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    public function view(User $user, Appeal $appeal): Response
    {
        if ($user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Developer])) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    public function create(User $user): Response
    {
        return $user->monthlyAppeals >= 3
            ? Response::denyAsNotFound(code: HttpFoundationResponse::HTTP_LOCKED, message: __('Maandelijkse limiet bereikt.'))
            : Response::allow();
    }
}
