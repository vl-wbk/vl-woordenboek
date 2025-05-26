<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Enums\FeedbackTrueFalse;
use App\Http\Requests\Support\StoreFeedbackRequest;
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
            'radioButtons' => FeedbackTrueFalse::class,
        ]);
    }

    #[Post(uri: 'feedback', name: 'feedback:store')]
    public function store(StoreFeedbackRequest $storeFeedbackRequest): RedirectResponse
    {
        dd($storeFeedbackRequest->all());
        return back();
    }
}
