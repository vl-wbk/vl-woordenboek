<?php

declare(strict_types=1);

namespace App\Models\Relations;

use App\Models\Preferences;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait UsesPreferences
{
    public function preferences(): MorphToMany
    {
        return $this->morphToMany(Preferences::class, 'preferable');
    }

    public function getPreference(string $preferenceKey): bool
    {
        if (! Preferences::query()->where('preference', $preferenceKey)->first()) {
            return true;
        }

        return ! $this->preferences()
            ->where('preference', $preferenceKey)
            ->exists();
    }

    public function enablePreference(string $preferenceKey): void
    {
        $preference = Preferences::where('preference', $preferenceKey)->firstOrFail();
        $this->preferences()->detach($preference->id);
    }

    public function disablePreference(string $preferenceKey): void
    {
        $preference = Preferences::where('preference', $preferenceKey)->firstOrFail();
        $this->preferences()->attach($preference->id);
    }
}
