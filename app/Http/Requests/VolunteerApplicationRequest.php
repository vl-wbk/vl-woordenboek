<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Data\VolunteerApplicationData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class VolunteerApplicationRequest extends FormRequest
{
    use WithData; 

    protected string $dataClass = VolunteerApplicationData::class;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'positie' => ['required'], 
            'motivatie' => ['required'], 
            'achtergrond' => ['required'],
        ];
    }
}
