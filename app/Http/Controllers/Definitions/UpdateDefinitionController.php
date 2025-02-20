<?php

declare(strict_types=1);

namespace App\Http\Controllers\Definitions;

use App\Actions\Articles\UpdateArticle;
use App\Http\Requests\Articles\UpdateArticleRequest;
use App\Models\Region;
use App\Models\Word;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * UpdateDefinitionController manages the display and processing of word definition updates.
 *
 * This controller provides methods for:
 *
 * - Displaying the form for editing a word's definition, including retrieving necessary data like available regions.
 * - Handling the submission of the update form, validating the input, ersisting the changes using an action class, and redirecting the user after a successful update.
 *
 * It utilizes route model binding to automatically resolve the Word model instance based on the request URL.
 * It also leverages form request validation to ensure data integrity.
 * Optionally, authorization can be implemented to restrict access to the update functionality.
 *
 * @package App\Http\Controllers\Definitions
 */
final readonly class UpdateDefinitionController
{
    /**
     * Display the form for editing a specific word's definition.
     * Retrieves available regions for a dropdown and the Word model instance.
     *
     * @param  Word $word  The Word model instance to be edited (injected via route model binding).
     * @return Renderable  The view for editing the definition.
     */
    public function edit(Word $word): Renderable
    {
        return view('definitions.update', [
            'regions' => Region::query()->pluck('name', 'id'),
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
     * @param  StoreArticle         $updateArticle          The action class responsible for updating the dictionary article.
     * @param  Word                 $word                   The Word model instance to be updated (injected via route model binding).
     * @return RedirectResponse                             Redirects to the dictionary article detail page after a successful update.
     */
    public function update(UpdateArticleRequest $updateArticleRequest, UpdateArticle $updateArticle, Word $word): RedirectResponse
    {
        $updateArticle($word, $updateArticleRequest->getData());

        return redirect()->action(DefinitionInformationController::class, $word);
    }
}
