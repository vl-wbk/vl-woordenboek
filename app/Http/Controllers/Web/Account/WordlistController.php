<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Actions\Account\CreateWordlist;
use App\Http\Requests\CreateWordlistRequest;
use App\Models\WordList;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\{Request, RedirectResponse};
use Spatie\RouteAttributes\Attributes\{Get, Post, Middleware};

#[Middleware(middleware: ['auth', 'verified', 'forbid-banned-user'])]
final class WordlistController
{
    use AuthorizesRequests;

    #[Get(uri: '/woordlijsten', name: 'word-lists:index')]
    public function index(Request $request): Renderable
    {
        return view('word-lists.index', data: [
            'user' => $request->user(),
            'lists' => $request->user()->wordLists()->withCount('words')->paginate(9),
        ]);
    }

    #[Get(uri: '/woordenlijst/{wordlist}', name: 'word-lists:show', middleware: ['can:view,wordlist'])]
    public function show(Request $request, WordList $wordlist): Renderable
    {
        return view('word-lists.show', data: [
            'user' => $request->user(),
            'wordList' => $wordlist,
            'words' => $wordlist->words()->paginate(),
        ]);
    }

    #[Get(uri: '/nieuwe-woordenlijst', name: 'word-lists:create')]
    public function create(Request $request): Renderable
    {
        return view('word-lists.create', data: [
            'user' => $request->user(),
        ]);
    }

    #[Post(uri: '/nieuwe-woordenlijst', name: 'word-lists:store', middleware: ['can:delete,wordlist'])]
    public function store(CreateWordlistRequest $createWordlistRequest, CreateWordlist $createWordlist): RedirectResponse
    {
        $createWordlist($createWordlistRequest->user(), $createWordlistRequest->getData()->toArray());
        flash('De woordlijst is met succes toegevoegd', 'alert-success');

        return to_route('word-lists:index');
    }

    #[Get(uri: '/woordenlijst/{wordlist}/wijzigen', name: 'word-lists:edit', middleware: ['can:update,wordlist'])]
    public function edit(WordList $wordlist): Renderable
    {
        throw new \LogicException('needs implementation');
    }

    #[Get(uri: '/woordenlijst/{wordlist}/verwijderen', name: 'word-lists:delete', middleware: ['can:delete,wordlist'])]
    public function delete(WordList $wordlist): RedirectResponse
    {
        throw new \LogicException('needs implementation');
    }
}
