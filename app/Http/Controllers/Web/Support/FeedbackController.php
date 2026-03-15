<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Support;

use App\Actions\Support\StoreFeedbackSubmission;
use App\Concerns\RateLimitSubmission;
use App\Enums\FeedbackTrueFalse;
use App\Http\Requests\Support\StoreFeedbackRequest;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

final class FeedbackController
{
    use RateLimitSubmission;

    protected string $rateLimitProfile = 'feedback';

    #[Get(uri: 'feedback', name: 'feedback:create')]
    public function create(): Renderable
    {
        return view('support.feedback', data: [
            'user' => Auth::user(),
            'radioButtons' => FeedbackTrueFalse::class,
        ]);
    }

    #[Post(uri: 'feedback', name: 'feedback:store')]
    public function store(StoreFeedbackRequest $storeFeedbackRequest, StoreFeedbackSubmission $storeFeedbackSubmission): RedirectResponse
    {
        $this->throttleSubmission($storeFeedbackRequest, 'feedback', function () use ($storeFeedbackSubmission, $storeFeedbackRequest): void {
            $storeFeedbackSubmission->execute($storeFeedbackRequest->getData());
            flash(text: "We hebben uw feedback opgeslagen. We gaan er ASAP mee aan de slag", class: 'alert alert-success');
        });

        return back();
    }
}
