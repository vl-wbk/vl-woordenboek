<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Enums\Articles\EtymologyStatus;
use App\Models\Article;
use Illuminate\Contracts\Support\Renderable;
use Spatie\RouteAttributes\Attributes\Get;

/**
 * WordOfTheDayController Class
 *
 * This controller is responsible for handling requests for the "Word of the Day" feature.
 * It retrieves the single article designated as the word of the day, increments its view count, and passes the data to the dedicated view for display.
 * It also counts the number of active etymologies associated with the word, excluding drafts, rejected, or archived entries.
 *
 * The controller uses Spatie's Route Attributes to define its route, making it a concise and clear entry point for the `/woord-van-de-dag` URI.
 *
 * @package App\Http\Controllers\Web\Articles
 */
final readonly class WordOfTheDayController
{
    /**
     * Handles the incoming request for the Word of the Day.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException if no article is marked as the word of the day.
     */
    #[Get(uri: '/woord-van-de-dag', name: 'word-information.wtod')]
    public function __invoke(): Renderable
    {
        $article = Article::where('wotd', true)->firstOrFail();
        $article->increment('views');

        return view('definitions.show', data: [
            'word' => $article,
            'etymologies' => $article->etymology()
                ->whereNotIn('status', [EtymologyStatus::Draft, EtymologyStatus::Rejected, EtymologyStatus::Archived])
                ->count(),
        ]);
    }
}
