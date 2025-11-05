<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PartOfSpeech;
use App\Models\User;
use Illuminate\Auth\Access\Response;

final readonly class PartOfSpeechPolicy
{
    public function view(User $user, PartOfSpeech $partOfSpeech): Response
    {
        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function viewAny(User $user): Response
    {
        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function create(User $user): Response
    {

        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function update(User $user, PartOfSpeech $partOfSpeech): Response
    {
        return $user->can('woordenboek_ondersteuning')
            ? Response::allow()
            : Response::deny();
    }

    public function delete(User $user, PartOfSpeech $partOfSpeech): Response
    {
        if ($user->can('woordenboek_ondersteuning')) {
            return Response::deny(message: __('U hebt geen machtiging om de woordsoort te verwijderen'));
        }

        if ($partOfSpeech->articles()->exists()) {
            return Response::deny(message: __('De woordsoort kan niet verwijderd worden omdat er artikelen aan zijn gekoppeld.'));
        }

        return Response::deny();
    }
}
