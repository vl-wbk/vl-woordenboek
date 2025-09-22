<?php

declare(strict_types=1);

namespace App\Actions\Contacts;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

final readonly class AttachContact
{
	public function handle(string $username): bool
	{
		$user = User::query()->where('name', $username)->firstOrFail();
		$authUser = auth()->user();
		
		// Use a single variable to store the error message
		$errorMessage = null;
		
		if ($authUser->name === $username) {
			$errorMessage = 'Je kunt jezelf niet toevoegen als contactpersoon';
		} elseif ($authUser->contacts->contains($user->id)) {
			$errorMessage = "$user->name is al reeds als contactpersoon toegevoegd.";
		}
		
		// If an error message was set, flash it and return false
		if ($errorMessage) {
			Session::flash('error_message', $errorMessage);
			return false;
		}
		
		// The transaction logic remains the same
		return DB::transaction(function () use ($authUser, $user): bool {
			$authUser->contacts()->attach($user);
			return true;
		});
	}
}