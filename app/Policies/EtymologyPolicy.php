<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Etymology;
use App\Models\User;

class EtymologyPolicy
{
    /**
     * Mag deze gebruiker een etymologie indienen voor dit woord?
     * Gebruik: Gate::allows('create', [Etymology::class, $word])
     */
    public function create(User $user, Article $word): bool
    {
        if (! $user->hasVerifiedEmail() || $user->banned_at) {
            return false;
        }

        // Er is al een gepubliceerde etymologie (editors passen die aan via "bewerken")
        if ($word->etymology()->exists()) {
            return false;
        }

        if ($user->isCommunityEditor()) {
            return true;
        }

        // Gewone gebruikers: maximaal één openstaand voorstel per woord
        return ! $word->etymologySuggestions()
            ->pending()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Beheerrechten: beoordelen, bewerken, intrekken en verwijderen.
     * Gebruik: $this->authorize('moderate', Etymology::class)
     */
    public function moderate(User $user): bool
    {
        return $user->isCommunityEditor();
    }

    // Filament roept deze standaardmethoden aan. Aanmaken gebeurt alleen
    // via de frontend (zie create() hierboven), dus daar is geen Filament-variant van.
    public function viewAny(User $user): bool
    {
        return $this->moderate($user);
    }

    public function view(User $user, Etymology $etymology): bool
    {
        return $this->moderate($user);
    }

    public function update(User $user, Etymology $etymology): bool
    {
        return $this->moderate($user);
    }

    public function delete(User $user, Etymology $etymology): bool
    {
        return $this->moderate($user);
    }

    public function deleteAny(User $user): bool
    {
        return $this->moderate($user);
    }
}
