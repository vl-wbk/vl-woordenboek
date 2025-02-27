<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use Illuminate\Contracts\Support\Renderable;

final readonly class SettingsController
{
    /**
     * @see \App\Actions\Fortify\UpdateUserPassword::class
     * @see \App\Actions\Fortify\UpdateUserProfileInformation::class
     */
    public function __invoke(): Renderable
    {
        return view('account.settings', [
            'user' => auth()->user()
        ]);
    }
}
