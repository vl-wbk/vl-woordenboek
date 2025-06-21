<?php

namespace App\Http\Requests\Posts;

use App\Data\GuestArticleDataObjectData;
use App\Models\Blog;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

final class StoreGuestArticleRequest extends FormRequest
{
    use WithData;

    protected string $dataClass = GuestArticleDataObjectData::class;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create', Blog::class);
    }

    public function rules(): array
    {
        return [
            'titel' => ['string', 'required', 'max:255'],
            'content' => ['required'],
        ];
    }
}
