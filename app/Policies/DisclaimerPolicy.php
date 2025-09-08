<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Disclaimer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Policy class for authorizing user actions on the Disclaimer model.
 *
 * This policy centralizes authorization logic for viewing, creating, updating, and deleting Disclaimer records.
 * It follows the Google PHP Style Guide for docblocks, providing clear descriptions of the purpose, parameters, and return values for each policy method.
 *
 * @see \App\Policies\ArticlePolicy::detachDisclaimer() For related authorization logic within the ArticlePolicy.
 * @see \App\Policies\ArticlePolicy::attachDisclaimer() For related authorization logic within the ArticlePolicy.
 *
 * @package App\Policies
 */
final class DisclaimerPolicy
{
    /**
     * A collection of standard prefixes used by Filament Shield and related authorization logic.
     * These prefixes map to concrete permissions like view_disclaimer, create_disclaimer, etc.
     *
     * @var list<string>
     */
    public static array $permissionPrefixes = ['view', 'view_any', 'create', 'update', 'delete', 'delete_any'];

    /**
     * Method executed before any other policy checks.
     *
     * If the user lacks the permission to manage articles in the system, deny access with a consistent message.
     * Returning null allows normal policy checks to proceed.
     *
     * @param  User $user		The currently authenticated user.
     * @return Response|null	A denial response or null to continue checking other policies.
     */
    public function before(User $user): ?Response
    {
        if ($user->cannot('page_Articles')) {
            return Response::deny(message: __('disclaimer-resource.policy.deny-messages.before'));
        }

        return null;
    }

    /**
     * Determine whether the user can view a specific disclaimer.
     * Grants access if the user has the 'view_disclaimer' permission, otherwise denies with a descriptive message.
     *
     * @param  User $user	The currently authenticated user.
     * @return Response		The authorization decision.
     */
    public function viewAny(User $user): Response
    {
        if ($user->can('view_any_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(message: __('disclaimer-resource.policy.deny-messages.viewAny'));
    }

    /**
     * Determine whether the user can view a specific disclaimer.
     * Grants access if the suer has the 'view_disclaimer' permission, otherwise denies with a descriptive message.
     *
     * @param  User 		$user		 The currently authenticated user.
     * @param  Disclaimer 	$disclaimer	 The disclaimer instance being accessed.
     * @return Response					 The authorization decision.
     */
    public function view(User $user, Disclaimer $disclaimer): Response
    {
        if ($user->can('view_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(message: __('disclaimer-resource.policy.deny-messages.view'));
    }

    /**
     * Determine whether the user can create a new disclaimer.
     * Grants access if the user has the 'create_disclaimer' permission, otherwise denies with a helpful message.
     *
     * @param  	User $user	The currently authenticated user.
     * @return  Response	The authorization decision.
     */
    public function create(User $user): Response
    {
        if ($user->can('create_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(message: __('disclaimer-resource.policy.deny-messages.create'));
    }

    /**
     * Determine whether the user can update an existing disclaimer.
     * Grants access if the user has the 'update_disclaimer' permission, otherwise denies with a user-friendly message.
     *
     * @param  User $user	The currently authenticated user.
     * @return Response		The authorization decision.
     */
    public function update(User $user): Response
    {
        if ($user->can('update_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(message: __('disclaimer-resource.policy.deny-messages.update'));
    }

    /**
     * Determine whether the user can delete a specific disclaimer.
     * Grants access if the user has the 'delete_disclaimer' permission; otherwise denies with an informative message.
     *
     * @param  User $user 	The currently authenticated user.
     * @return Response		The authorization decision.
     */
    public function delete(User $user): Response
    {
        if ($user->can('delete_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(__('disclaimer-resource.policy.deny-messages.delete'));
    }

    /**
     * Determine whether the user can delete multiple disclaimers at once.
     * Grants access if the user has the 'delete_any_disclaimer' permission, otherwise denies with a clear message.
     *
     * @param  User $user	The currently authenticated user.
     * @return Response		The authorization decision.
     */
    public function deleteAny(User $user): Response
    {
        if ($user->can('delete_any_disclaimer')) {
            return Response::allow();
        }

        return Response::deny(__('disclaimer-resource.policy.deny-messages.deleteAny'));
    }
}
