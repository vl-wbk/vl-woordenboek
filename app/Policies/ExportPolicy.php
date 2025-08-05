<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Auth\Access\Response;

final readonly class ExportPolicy
{
    public function view(User $user, Export $export): Response
    {
        if ($export->user()->is($user)) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_view_message', replace: [
                'resource' => __('authorization.resources.export'),
            ]),
        );
    }

    /**
     * @todo Document policy
     * @todo Implement policy on the action class
     */
    public function create(User $user): Response
    {
        if ($user->can('export_article')) {
            return Response::allow();
        }

        return Response::deny(
            message: __('authorization.policies.responses.deny_create_message', replace: [
                'resource' => __('authorization.resources.export'),
            ]),
        );
    }
}
