<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\ArticleReportData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

/**
 * StoreReportRequest validates and transforms the data submitted when a user reports an article.
 *
 * This class leverages the Spatie Laravel Data package to automatically convert incoming request data into a strongly typed ArticleReportData data transfer object.
 * This ensures that the data is validated and structured consistently throughout the application.
 *
 * By separating the data transformation and validation logic from business logic, this class helps maintain cleaner code and promotes easier updates.
 * Open-source contributors and future developers will find that this design choice enhances maintainability and scalability.
 */
final class StoreReportRequest extends FormRequest
{
    /** @use WithData<ArticleReportData> */
    use WithData;

    /**
     * The data transfer object class used to transform the request payload.
     *
     * This property, set to ArticleReportData::class, instructs the WithData trait on which DTO to use for automating the
     * transformation of the incoming request data. It provides strong typing and validation out-of-the-box.
     *
     * @return string
     */
    protected string $dataClass = ArticleReportData::class;

    /**
     * Get the validation rules that apply to the report request.
     *
     * This method defines the rules for validating the incoming request.
     * Currently, it mandates that the 'melding' field is provided.
     * Future developers may extend this method to include additional rules as the reporting requirements evolve.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['melding' => ['required']];
    }
}
