<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
class RegistrationRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'phone_number' => 'required',
            'email' => 'email|required|unique:users',
            'password' => ['required', 'string', Password::min(6)->numbers()->letters()
            // ->uncompromised()
            ],
            'confirm_password' => 'required|same:password'
        ];
    }
}
