<?php

namespace App\Http\Requests\Blog;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Data\Blog\GuestArticleData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreGuestArticleRequest extends FormRequest
{
	use WithData;
	
	protected string $dataClass = GuestArticleData::class;
	
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titel' => ['required', 'max:255'],
			'artikel' => ['required', 'max:255'],
        ];
    }
}
