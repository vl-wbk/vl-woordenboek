<?php

declare(strict_types=1);

	namespace App\Http\Controllers\Web\Messages;

	use App\Actions\Contacts\AttachContact;
	use App\Http\Requests\Account\StoreContactRequest;
	use App\Models\User;
	use Illuminate\Contracts\Support\Renderable;
	use Illuminate\Database\Eloquent\Builder;
	use Illuminate\Http\RedirectResponse;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Session;
	use Spatie\RouteAttributes\Attributes\Get;
	use Spatie\RouteAttributes\Attributes\Middleware;
	use Spatie\RouteAttributes\Attributes\Post;
    use Throwable;

	#[Middleware(middleware: ['auth', 'verified', 'forbid-banned-user'])]
	final readonly class ContactsController
	{
		#[Get(uri: '/contacten', name: 'contacts:index')]
		public function index(Request $request): Renderable
		{
			$contactsQuery = auth()->user()->contacts();
			$contactsQuery->when($request->filled('zoekterm'), function (Builder $builder) use ($request): void {
				$builder->whereHas('contacts', function (Builder $builder) use ($request): void {
					$builder->where('name', 'LIKE', "%{$request->get('zoekerm')}%");
				});
			});

			return view('account.contacts.index', data: [
				'contacts' => $contactsQuery->paginate()
			]);
		}

		#[Get(uri: '/contacten/toevoegen', name: 'contacts:create')]
		public function create(): Renderable
		{
			return view('account.contacts.create');
		}

        /**
         * @throws Throwable when the concept couldn't be stored successfully
         */
		#[Post(uri: '/contacten/toevoegen', name: 'contacts:store')]
		public function store(StoreContactRequest $storeContactRequest, AttachContact $attachContact): RedirectResponse
		{
			$username = $storeContactRequest->get('gebruikersnaam');

			if ($attachContact->handle($username)) {
				Session::flash('success_message', "Je hebt $username toegevoegd als contactpersoon.");
			};

			return back();
		}

		#[Get(uri: '/contact/{contact}/verwijder', name: 'contacts:delete')]
		public function destroy(User $contact): RedirectResponse
		{
			if (auth()->user()->removeContact($contact)) {
				Session::flash('success_message', "$contact->name is verwijderd als contactpersoon");
			}

			return back();
		}
	}
