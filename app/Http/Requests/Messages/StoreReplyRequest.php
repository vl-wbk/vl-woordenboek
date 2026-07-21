<?php

declare(strict_types=1);

namespace App\Http\Requests\Messages;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Data\Message\ReplyDataObject;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Spatie\LaravelData\WithData;

/**
 * StoreReplyRequest
 *
 * This class encapsulates the validation and authorization logic required to post a reply to an existing thread.
 * It serves as a bridge between the incoming HTTP payload and the application's domain layer by utilizing Spatie's Laravel-Data package.
 *
 * Developers:
 * This request expects a 'thread' parameter in the route.
 * The validated data is automatically mapped to a DataObject, allowing for type-safe interaction  within your controllers or service classes.
 *
 * @property-read Thread $thread The Thread model instance resolved via route model binding.
 */
final class StoreReplyRequest extends FormRequest
{
    /** @use WithData<ReplyDataObject> */
    use WithData;

    protected string $dataClass = ReplyDataObject::class;

    /**
     * Determine if the user is authorized to perform this action.
     *
     * Authorization is delegated to the 'reply' method within the ThreadPolicy.
     * It verifies that the authenticated user has the necessary permissions to interact with the specific Thread instance provided in the route.
     *
     * @return bool True is the user is permitted to reply to the thread.
     */
    public function authorize(): bool
    {
        return Gate::allows('reply', $this->thread);
    }

    /**
     * Get the validçation rules that apply to the request.
     * These rules define the shape of the incoming data before it is passed to the ReplyDataObject.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ['bericht' => ['required']];
    }
}
