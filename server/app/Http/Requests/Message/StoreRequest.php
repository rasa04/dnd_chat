<?php

declare(strict_types=1);

namespace App\Http\Requests\Message;

use App\Enum\MessageTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

final class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string',
            'game_id' => 'required|integer',
            'type' => sprintf(
                'string|in:%s',
                implode(',', MessageTypeEnum::getValues())
            ),
        ];
    }
}
