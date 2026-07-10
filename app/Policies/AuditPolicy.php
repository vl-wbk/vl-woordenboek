<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\UserTypes;
use Illuminate\Auth\Access\Response;
use OwenIt\Auditing\Models\Audit;

final readonly class AuditPolicy
{
    /** @var string Ability constant for revert*/
    public const Revert = 'revert';

    /** @var string Ability constant for viewing correction logs. */
    public const ViewCorrection = 'viewCorrection';

    /**
     * Determine whether the authenticated user can revert the specified audit record.
     *
     * Grants permission if the audit event represents an update and the user belongs to
     * elevated roles authorized to rollback model states (Editor-in-chief, Developer, or Administrator).
     *
     * @param  User  $user  The authenticated user checking authorization.
     * @param  Audit $audit The specific audit instance to be evaluated.
     * @return Response     An authorization response allowing access or denying with a 404 facade.
     */
    public function revert(User $user, Audit $audit): Response
    {
        $hasCorrectRole = $user->user_type->in(enums: [
            UserTypes::EditorInChief,
            UserTypes::Developer,
            UserTypes::Administrators
        ]);

        return ($audit->event === 'updated' && $hasCorrectRole)
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the authenticated user can view the correction details for an audit.
     *
     * Grants permission only if the target audit contains an explicitly documented reason
     * for correction and the viewing user is an internal administrative operation (not a standard user).
     *
     * @param  User  $user  The authenticaed user checking authorization.
     * @param  Audit $audit The specific audit instance to be evaluated.
     * @return Response     An authorization response allowing to access or denying with a 404 facade.
     */
    public function viewCorrection(User $user, Audit $audit): Response
    {
        return ($audit->correction_reason && $user->user_type->isNot(UserTypes::Normal))
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
