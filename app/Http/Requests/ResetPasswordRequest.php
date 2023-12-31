<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\TokenExpirationTimeRule;


class ResetPasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', 'regex:/^[0-9a-zA-z-_]{8,32}$/', 'confirmed'],
            'password_confirmation' => ['required', 'same:password'],
            'reset_token' => ['required', new TokenExpirationTimeRule],
        ];
    }
}
