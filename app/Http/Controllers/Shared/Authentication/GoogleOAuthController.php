<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\RouteAttributes\Attributes\Get;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Exception;

final readonly class GoogleOAuthController
{
	public function redirect(): RedirectResponse
	{
		return Socialite::driver('google')->redirect();
	}

	public function callback(): RedirectResponse
	{
		try {
			$googleUser = Socialite::driver('google')->stateless()->user();
		} catch (Exception $exception) {
			return redirect('/login');
		}
		
		if ($existing = User::where('email', $googleUser->email)->first()) {
			auth()->login($existing);
		} else {
		
		}
		
		$user = User::create([
			'name' => $googleUser->name,
			'email' => $googleUser->email,
			'password' => encrypt(Str::random()),
			'provider' => 'google',
			'provider_id' => $googleUser->id
		]);
		
		Auth::login($user);
		
		return redirect('/');
	}
}