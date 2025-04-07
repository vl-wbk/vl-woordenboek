<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\SuggestionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

/**
 * StoreSuggestionRequest
 *
 * This class extends the FormRequest to validate and transform incoming data for creating a new suggestion.
 * It utilizes Spatie's Laravel Data package for easy data transfer object (DTO) creation from request data.
 */
final class StoreSuggestionRequest extends FormRequest
{
    /**
     * Trait to automatically convert the request data into a SuggestionData object.
     *
     * @use WithData<SuggestionData>
     */
    use WithData;

    /**
     * The data class to use for automatic data conversion.
     */
    protected string $dataClass = SuggestionData::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * This method defines the validation rules for each field in the suggestion submission form.
     * It ensures that required fields are present and that data types and lengths are appropriate.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> An array of validation rules, where the keys are field names and the values are validation rules.
     */
    public function rules(): array
    {
        return [
            'woord' => ['required', 'max:255'],
            'kenmerken' => [],
            'beschrijving' => ['required'],
            'regio' => ['required', 'array', 'min:1'],
            'voorbeeld' => ['required'],
            'woordsoort' => [],
        ];
    }
}
