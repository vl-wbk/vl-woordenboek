<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * @todo document policy class
 */
final class FeedbackPolicy
{
    /**
     * @var list<string>
     */
    public static array $permissionPrefixes = ['view_any', 'view', 'delete', 'delete_any', 'change_status'];

    public function viewAny(User $user): Response
    {
		if ($user->can('view_any_feedback')) {
			return Response::allow();
		}
		
		return Response::deny(message: __('U hebt geen machtiging om het feedback overzicht te bekijken.'));
    }

    public function view(User $user, Feedback $feedback): Response
    {
        if ($user->can('view_feedback')) {
			return Response::allow();
		}
		
		return Response::deny(message: __('U hebt geen machtiging om dit feedback bericht te bekijken.'));
    }

    public function delete(User $user): Response
    {
        if ($user->can('delete_feedback')) {
			return Response::allow();
		}
		
		return Response::deny(message: __('U hebt geen machtiging om dit feedback bericht te verwijderen.'));
    }

    public function markAs(User $user): Response
    {
        if ($user->can('change_status_feedback')) {
			return Response::allow();
		}
		
		return Response::deny(message: __('U hebt geen machtiging om dit feedback bericht te markeren als opgelost.'));
    }

    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_feedback')) {
			return Response::allow();
		}
		
		return Response::deny(message: __('U hebt geen machtiging om feedback te verwijderen.'));
    }
}
