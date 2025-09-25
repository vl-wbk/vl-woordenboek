<?php

declare(strict_types=1);
	
	namespace App\Http\Controllers\Web\Messages;
	
	use App\Actions\Messages\SendMessage;
	use App\Http\Requests\Messages\SendMessageRequest;
	use App\Models\User;
	use App\Queries\Messages\SelectInboxQuery;
	use Cmgmyr\Messenger\Models\Thread;
	use Illuminate\Contracts\Support\Renderable;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Session;
	use Spatie\RouteAttributes\Attributes\Get;
	use Spatie\RouteAttributes\Attributes\Middleware;
	use Spatie\RouteAttributes\Attributes\Post;
	
	#[Middleware(middleware: ['auth', 'verified', 'forbid-banned-user'])]
	final readonly class InboxController
	{
		#[Get(uri: 'inbox', name: 'profile:inbox')]
		public function __invoke(Request $request, SelectInboxQuery $selectInboxQuery): Renderable
		{
			return view('account.messages.index', data: [
				'threads' => $selectInboxQuery->compose($request)->paginate(),
			]);
		}
		
		#[Get(uri: '/inbox/nieuw-bericht', name: 'inbox:create')]
		public function create(Request $request): Renderable
		{
			$reciever = User::query()->where('id', $request->get('participant'))->first();
			
			return view('account.messages.create', data: [
				'reciever' => $reciever,
			]);
		}
		
		#[Get(uri: '/inbox/thread/{thread}', name: 'inbox:show', middleware: ['can:view,thread'])]
		public function show(Thread $thread): Renderable
		{
			$userId = auth()->id();
			$users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
			$participantRecord = $thread->participants()->where('user_id', $userId)->first();
			
			$thread->markAsRead($userId);
			$thread->loadMissing(['participants.user']);
			
			return view('account.messages.show', data: [
				'thread' => $thread,
				'userParticipantRecord' => $participantRecord,
				'messages' => $thread->messages()->with(['user'])->paginate(),
				'users' => $users,
			]);
		}
		
		#[Post(uri: '/inbox/nieuw-bericht', name: 'inbox:store')]
		public function store(SendMessageRequest $sendMessageRequest, SendMessage $sendMessage): RedirectResponse
		{
			$thread = $sendMessage->handle($sendMessageRequest->getData());
			// Session::flash('success_message', 'We hebben je bericht verzonden naar ' . $sendMessageRequest->getData()->getReceiver());
			
			return redirect()->action([self::class, 'show'], $thread);
		}
	}