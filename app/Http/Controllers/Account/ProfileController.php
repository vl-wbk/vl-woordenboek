<?php

declare(strict_types=1);

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

final readonly class ProfileController
{
    public function __invoke(User $user): Renderable
    {
        return view('account.index', compact('user'));
    }
}
