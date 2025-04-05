<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Articles;

use App\Actions\Articles\UpdateArticle;
use App\Http\Controllers\Web\Articles\DictionaryArticleController;
use App\Http\Requests\Articles\UpdateArticleRequest;
use App\Models\Region;
use App\Models\Article;
use App\Models\PartOfSpeech;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Patch;

/**
 * RequestArticleChangeController manages the display and processing of word definition updates.
 *
 * This controller provides methods for:
 *
 * - Displaying the form for editing a word's definition, including retrieving necessary data like available regions.
 * - Handling the submission of the update form, validating the input, ersisting the changes using an action class, and redirecting the user after a successful update.
 *
 * It utilizes route model binding to automatically resolve the Article model instance based on the request URL.
 * It also leverages form request validation to ensure data integrity.
 * Optionally, authorization can be implemented to restrict access to the update functionality.
 *
 * @package App\Http\Controllers\Web\Articles
 */
#[Middleware(middleware: ['auth', 'forbid-banned-user'])]
final readonly class RequestArticleChangeController
{
    /**
     * Display the form for editing a specific word's definition.
     * This method retrieves available regions and parts of speech for dropdown selection, and passes them along with the Article model instance to the edit view.
     *
     * @param  Article $word  The Article model instance to be edited (injected via route model binding).
     * @return Renderable     The view for editing the definition.
     */
    #[Get(uri: '/artikel/{word}/aanpassen', name: 'definitions.update')]
    public function edit(Article $word): Renderable
    {
        return view('definitions.update', [
            'regions' => Region::query()->pluck('name', 'id'),
            'partOfSpeeches' => PartOfSpeech::query()->pluck('name', 'id'),
            'word' => $word
        ]);
    }

    /**
     * Update the specified word's definition in the database.
     *
     * Validates the request data, uses the UpdateArticle action to persist the changes,
     * and redirects the user to the word's detail page upon successful update.
     *
     * @param  UpdateArticleRequest $updateArticleRequest   The validated request data for updating the definition.
     * @param  UpdateArticle        $updateArticle          The action class responsible for updating the dictionary article.
     * @param  Article              $word                   The Article model instance to be updated (injected via route model binding).
     * @return RedirectResponse                             Redirects to the dictionary article detail page after a successful update.
     */
    #[Patch(uri: '/artikel/{word}/aanpassen', name: 'article.update')]
    public function update(UpdateArticleRequest $updateArticleRequest, UpdateArticle $updateArticle, Article $word): RedirectResponse
    {
        $updateArticle($word, $updateArticleRequest->getData());

        return redirect()->action(DictionaryArticleController::class, $word);
    }
}
