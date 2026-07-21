<?php

declare(strict_types=1);

namespace App\Http\Requests\Articles;

use App\Data\SuggestionData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreConceptRequest extends FormRequest
{
    /** @use WithData<SuggestionData> */
    use WithData;

    protected string $dataClass = SuggestionData::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'woord' => ['required', 'max:255'],
            'kenmerken' => [],
            'beschrijving' => ['required'],
            'regio' => ['required', 'array', 'min:1'],
            'woordsoort' => [],
            'voorbeeldzin'             => ['required', 'array', 'min:1'],
            'voorbeeldzin.*.bron'       => ['required', 'string', 'max:255'],
            'voorbeeldzin.*.waarde'     => ['required', 'string', 'max:255'],
        ];
    }

    public function getSubmissionAction(): string
    {
        return $this->input('action');
    }

    public function messages(): array
    {
        return [
            'voorbeeldzin.required'         => 'Ten minste één sleutel-voorbeeldzin paar is vereist.',
            'voorbeeldzin.*.bron.required'  => 'Elk paar moet een bron hebben.',
            'voorbeeldzin.*.waarde.required'=> 'Elk paar moet een voorbeeldzin hebben.',
        ];
    }
}
