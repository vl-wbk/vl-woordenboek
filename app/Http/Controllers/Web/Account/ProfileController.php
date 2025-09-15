<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ProfileController
{
	#[Get(uri: 'account/{user}', name: 'account:public', middleware: ['auth', 'forbid-banned-user', 'verified'])]
	public function __invoke(User $user): Renderable
	{
		return view('account.index', data: [
			'user' => $user,
		]);
	}
}