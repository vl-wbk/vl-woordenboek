<?php

declare(strict_types=1);
	
	namespace App\Http\Controllers\Web\Account;
	
	use App\Models\User;
	use Illuminate\Contracts\Support\Renderable;
	use Illuminate\Http\Request;
	use Spatie\RouteAttributes\Attributes\Get;
	
	final readonly class ProfileController
	{
		#[Get(uri: 'account/{user}', name: 'account:public', middleware: ['auth', 'forbid-banned-user', 'verified'])]
		public function show(Request $request, User $user): Renderable
		{
			return view('account.index', data: [
				'user' => $user,
				'contributions' => $user->searchContributions('suggestions', $request->string('zoekterm', ), 'word'),
			]);
		}
		
		#[Get(uri: 'account/{user}/etymologie', name: 'account:public:etymologies', middleware: ['auth', 'forbid-banned-user', 'verified'])]
		public function etymologies(Request $request, User $user): Renderable
		{
			return view('account.etymologies', data: [
				'user' => $user,
				'contributions' => $user->searchContributions('etymologies', $request->string('zoekterm'), 'etymology'),
			]);
		}
		
		#[Get(uri: 'account/{user}/artikelen', name: 'account:public:articles', middleware: [
			'auth',
			'forbid-banned-user',
			'verified'
		])]
		public function articles(User $user): Renderable
		{
		}
	}