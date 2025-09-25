<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Messages;

use App\Actions\Messages\AddParticipant;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;

#[Middleware(middleware: ['auth', 'verified', 'forbid-banned-user'])]
final readonly class ParticipantController
{
	use AuthorizesRequests;
	
	#[Get(uri: '/thread/{thread}/verwijder-persoon/{participant}', name: 'thread:leave')]
	public function leave(Thread $thread, Participant $participant): RedirectResponse
	{
		Gate::any(['leave', 'remove-participants'], [$thread, $participant]);
		
		$thread->removeParticipant($participant->user->id);
		
		return redirect()->back();
	}
	
	#[Post('/thread/{thread}/persoon-toevoegen', name: 'thread:add-participant', middleware: ['can:add-participants,thread'])]
	public function create(Request $request, Thread $thread, AddParticipant $addParticipant): RedirectResponse
	{
		$addParticipant($thread, $request);
		return back();
	}
}