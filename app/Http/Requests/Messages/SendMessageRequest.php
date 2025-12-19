<?php

declare(strict_types=1);

namespace App\Http\Requests\Messages;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Data\MessageObjectData;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\LaravelData\WithData;

/**
 * SendMessageRequest
 *
 * This class handles the validation and structural transformation for sending new messages.
 * It serves as a protective layer for the application, ensuring that incoming request data is not only valid but also properly cast into a MessageObjectData DTO.
 * 
 * Open Source Contributors: 
 * If you are adding new fields to the messaging system (e.g., attachments or priority levels), you must update both the rules() method here 
 * and the corresponding properties in the MessageObjectData class to maintain consistency.
 * 
 * @package App\Http\Requests\Messages
 */
final class SendMessageRequest extends FormRequest
{
    /**
     * Integrates the Spatie Laravel-Data bridge.
     * This trait allows the controller to access a fully hydrated and typed 
     * Data Object instance using `$request->getData()`, reducing the need for manual array mapping in the service layer.
     * 
     * @use WithData<MessageObjectData>
     */
    use WithData;

    /**
     * Defines the target Data Transfer Object (DTO) class.
     * The validated data from this request will be piped into this class  to ensure type safety throughout the message-sending process.
     * 
     * @var class-string<MessageObjectData>
     */
    protected string $dataClass = MessageObjectData::class;

    /**
     * Get the validation rules that apply to the request. 
     * These rules enforce the following constraints:
     * 
     * - 'ontvanger':  Must be a valid username existing in the users table. 
     * - 'onderwerp':  A required string to prevent empty subjects, capped at 255 chars.
     * - 'bericht':    The core content of the message; required for submission.
     * 
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ontvanger' => ['required', 'exists:App\Models\User,name'],
            'onderwerp' => ['required', 'max:255'],
            'bericht' => ['required'],
        ];
    }
}
