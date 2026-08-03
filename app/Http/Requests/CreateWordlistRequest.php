<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Data\WordlistData;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class CreateWordlistRequest extends FormRequest
{
    use WithData;

    protected string $dataClass = WordlistData::class;

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'naam' => ['required', 'max:255'],
        ];
    }
}
