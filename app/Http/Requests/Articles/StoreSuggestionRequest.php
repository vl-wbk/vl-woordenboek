<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\SuggestionData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreSuggestionRequest extends FormRequest
{
    /** @use WithData<SuggestionData> */
    use WithData;

    protected string $dataClass = SuggestionData::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'woord' => ['required', 'max:255'],
            'kenmerken' => [],
            'beschrijving' => ['required'],
            'regio' => ['required', 'array', 'min:1'],
            'voorbeeld' => ['required'],
        ];
    }
}
