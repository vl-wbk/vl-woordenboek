<?php

declare(strict_types=1);

namespace App\Policies;

use App\Features\BetaProgramFeature;
use App\Models\CorrectionProposal;
use App\Models\User;
use App\States\Articles\Corrections\ApprovedState;
use App\States\Articles\Corrections\PendingState;
use App\UserTypes;
use Illuminate\Auth\Access\Response;
use Laravel\Pennant\Feature;

final readonly class CorrectionProposalPolicy
{
    public const Approve = 'approve';

    public function before(User $user): ?Response
    {
        if (Feature::for($user)->active(BetaProgramFeature::class)) {
            return null;
        }

        return Response::denyAsNotFound();
    }

    public function create(User $user): Response
    {
        return Response::allow();
    }

    public function viewAny(User $user): Response
    {
        return $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Editor, UserTypes::EditorInChief, UserTypes::Developer])
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om een overzicht van correctie te bekijken'));
    }

    public function view(User $user, CorrectionProposal $correctionProposal): Response
    {
        $hasCorrectUserType = $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Editor, UserTypes::EditorInChief, UserTypes::Developer]); 
        $isEditable = in_array($correctionProposal->state, [ApprovedState::class]);

        return ($hasCorrectUserType && $isEditable)
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om de gegevens van een correctie te bekijken'));
    }

    public function update(User $user, CorrectionProposal $correctionProposal): Response
    {
        $hasCorrectUserType = $user->user_type->in(enums: [UserTypes::Administrators, UserTypes::Editor, UserTypes::EditorInChief, UserTypes::Developer]); 
        $isEditable = $correctionProposal->state == PendingState::class;

        return ($hasCorrectUserType && $isEditable)
            ? Response::allow()
            : Response::deny(message: __('U hebt geen machtiging om de gegevens van een correctie te bekijken'));
    }

    public function approve(User $user): bool
    {
        return $user->user_type->in(enums: [
            UserTypes::EditorInChief, UserTypes::Developer, UserTypes::Administrators
        ]);
    }
}
