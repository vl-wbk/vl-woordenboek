<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ProfileController
{
	#[Get(uri: 'account/{user}', name: 'account:public', middleware: ['auth', 'forbid-banned-user', 'verified'])]
	public function show(User $user): Renderable
	{
		return view('account.index', data: [
			'user' => $user,
		]);
	}
	
	#[Get(uri: 'account/{user}/etymologie', name: 'account:public:etymologies', middleware: ['auth', 'forbid-banned-user', 'verified'])]
	public function etymologies(User $user): Renderable
	{
	
	}
	
	#[Get(uri: 'account/{user}/artikelen', name: 'account:public:articles', middleware: ['auth', 'forbid-banned-user', 'verified'])]
	public function articles(User $user): Renderable
	{
	
	}
}