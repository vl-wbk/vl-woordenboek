<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Actions\Account\CreateWordlist;
use App\Concerns\HandlesDatabaseTransactions;
use App\Http\Requests\CreateWordlistRequest;
use App\Models\Article;
use App\Models\WordList;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\{Request, RedirectResponse};
use Spatie\RouteAttributes\Attributes\{Get, Post, Middleware, Patch};

#[Middleware(middleware: ['auth', 'verified', 'forbid-banned-user'])]
final class WordlistController
{
    use AuthorizesRequests;
    use HandlesDatabaseTransactions;

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
            'words' => $wordlist->words()->published()->paginate(6),
            'wordList' => $wordlist->loadCount(['words' => function ($query): void {
                $query->published();
            }]),
        ]);
    }

    #[Get(uri: '/nieuwe-woordenlijst', name: 'word-lists:create')]
    public function create(Request $request): Renderable
    {
        return view('word-lists.create', data: [
            'user' => $request->user(),
        ]);
    }

    #[Post(uri: '/nieuwe-woordenlijst', name: 'word-lists:store')]
    public function store(CreateWordlistRequest $createWordlistRequest, CreateWordlist $createWordlist): RedirectResponse
    {
        $wordlist = $createWordlist($createWordlistRequest->user(), $createWordlistRequest->getData()->toArray());
        flash('De woordlijst is met succes toegevoegd', 'alert-success');

        return to_route('word-lists:show', $wordlist);
    }

    #[Get(uri: '/woordenlijst/{wordlist}/wijzigen', name: 'word-lists:edit', middleware: ['can:update,wordlist'])]
    public function edit(Request $request, WordList $wordlist): Renderable
    {
        return view('word-lists.edit', data: [
            'user' => $request->user(),
            'wordList' => $wordlist
        ]);
    }

    #[Patch(uri: '/woordenlijst/{wordlist}/aanpassen', name: 'word-lists:update', middleware: ['can:update,wordlist'])]
    public function update(CreateWordlistRequest $createWordlistRequest, WordList $wordlist): RedirectResponse
    {
        $this->executeInTransaction(callback: fn (): bool|int => $wordlist->update($createWordlistRequest->getData()->toArray()));

        return to_route('word-lists:show', $wordlist);
    }

    #[Get(uri: '/woordenlijst/{wordlist}/verwijderen', name: 'word-lists:delete', middleware: ['can:delete,wordlist'])]
    public function delete(WordList $wordlist): RedirectResponse
    {
        $this->executeInTransaction(callback: fn (): int|bool => $wordlist->delete());

        return to_route('word-lists:index');
    }

    #[Post('/{article}/sync-woordenlijst', name: 'word-lists:sync')]
    public function sync(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'lists' => ['nullable', 'array'],
            'lists.*' => ['integer', 'exists:word_lists,id'],
        ]);

        $selectedListIds = $validated['lists'] ?? [];

        // Beveiliging: filter naar alleen lijsten van de ingelogde gebruiker,
        // anders zou iemand via devtools een list_id van een ander kunnen posten
        $allowedListIds = auth()->user()->wordLists()
            ->whereIn('id', $selectedListIds)
            ->pluck('id');

        $article->wordLists()->sync($allowedListIds);

        return back()->with('success', 'Themalijsten bijgewerkt.');
    }
}
