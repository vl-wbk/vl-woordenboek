<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\RedirectResponse;

final readonly class GoogleOAuthController
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback(): RedirectResponse
	{
		$googleUser = Socialite::driver('google')->stateless()->user();
		
		$user = User::updateOrCreate([
			'provider_id' => $googleUser->id,
		], [
			'firstname' => $googleUser->name,
			'email' => $googleUser->email,
			'password' => encrypt(Str::random()),
			'provider' => 'google',
			'provider_id' => $googleUser->id
		]);
		
		dd($user);
		
		Auth::login($user);
		
		return redirect('/');
	}
}