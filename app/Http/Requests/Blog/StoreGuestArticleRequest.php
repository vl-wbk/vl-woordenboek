<?php

namespace App\Http\Requests\Blog;

use App\Data\Blog\GuestArticleData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreGuestArticleRequest extends FormRequest
{
	use WithData;
	
	protected string $dataClass = GuestArticleData::class;
	
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titel' => ['required', 'max:255'],
			'artikel' => ['required', 'max:255'],
        ];
    }
}
