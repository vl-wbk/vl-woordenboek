<?php

declare(strict_types=1);

namespace App\Actions\Messages;

use App\Models\User;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

final readonly class AddParticipant
{
	public function __invoke(Thread $thread, Request $request): void
	{
		try {
			$user = User::query()->where('name', $request->get('gebruikersnaam'))->firstOrFail();
			
			if ($thread->hasParticipant($user->id)) {
				Session::flash('error_message', "De gebruiker is al actief in deze conversatie.");
				return;
			}
			
			$thread->addParticipant($user->id);
			Session::flash('success_message', $request->get('gebruikersnaam') . " neemt nu deel aan deze conversatie.");
		} catch (ModelNotFoundException $modelNotFoundException) {
			Session::flash('error_message', "Er bestaat geen gebruiker in het Vlaams Woordenboek met de opgegeven gebruikersnaam.");
			return;
		}
	}
}