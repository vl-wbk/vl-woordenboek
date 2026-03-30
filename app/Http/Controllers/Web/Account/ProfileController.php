<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Account;

use App\Models\Article;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

final readonly class ProfileController
{
    #[Get(uri: 'account/{user}', name: 'account:public', middleware: ['auth', 'forbid-banned-user', 'verified'])]
    public function show(Request $request, User $user): Renderable
    {
        $searchTerm = $request->string('zoekterm');

        return view('account.index', data: [
            'user' => $user,
            'randomArticle' => Article::published()->inRandomOrder()->first(),
            'contributions' => Article::where('author_id', $user->id)->with('labels')->published()->where('word', 'LIKE', "%{$searchTerm}%")->paginate(7),
        ]);
    }
}
