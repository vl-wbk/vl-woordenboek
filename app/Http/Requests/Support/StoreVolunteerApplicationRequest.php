<?php

declare(strict_types=1);

namespace App\Http\Requests\Support;

use App\Data\VolunteerApplicationData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelData\WithData;

final class StoreVolunteerApplicationRequest extends FormRequest
{
    /**
     * @use WithData<VolunteerApplicationData>
     */
    use WithData;

    protected string $dataClass = VolunteerApplicationData::class;

    public function authorize(): bool
    {
        return Gate::allows('apply', $this->volunteerPosition);
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'voornaam' => ['required', 'max:255'],
            'achternaam' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email']
        ];
    }
}
