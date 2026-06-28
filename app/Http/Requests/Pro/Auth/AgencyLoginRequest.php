<?php

namespace App\Http\Requests\Pro\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AgencyLoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'pin' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'username' => 'username',
            'pin' => 'PIN',
        ];
    }
}
