<?php

declare(strict_types=1);

namespace App\Http\Requests\Definitions;

use App\Data\DefinitionDataObject;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

class StoreDefinitionsRequest extends FormRequest
{
    use WithData;

    protected string $dataClass = DefinitionDataObject::class;

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
