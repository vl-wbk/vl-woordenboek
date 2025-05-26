<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

final readonly class FeedbackController
{
    #[Get(uri: 'feedback', name: 'feedback:create')]
    public function create(): Renderable
    {
        return view('support.feedback', data: [
            'user' => Auth::user(),
        ]);
    }

    #[Post(uri: 'feedback', name: 'feedback:store')]
    public function store(): RedirectResponse
    {
        return back();
    }
}
