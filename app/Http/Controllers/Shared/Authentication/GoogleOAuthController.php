<?php

declare(strict_types=1);

namespace App\Http\Controllers\Shared\Authentication;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Two\User as OAuth2User;
use Symfony\Component\HttpFoundation\Response;

final readonly class GoogleOAuthController
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(): RedirectResponse
    {
        try {
            /** @var OAuth2User $googleUser */
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (InvalidStateException $invalidStateException) {
            abort(Response::HTTP_BAD_REQUEST, $invalidStateException->getMessage());
        }

        $user = User::query()
            ->updateOrCreate(
                attributes: ['email' => $googleUser->email],
                values: $this->registrationData($googleUser),
            );

        auth()->login($user);

        return redirect('/');
    }

    private function registrationData(OAuth2User $googleUser): array
    {
        return [
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'password' => Hash::make(Str::random()),
            'google_id' => $googleUser->getId(),
            'google_token' => $googleUser->token,
            'google_refresh_token' => $googleUser->refreshToken,
        ];
    }
}
