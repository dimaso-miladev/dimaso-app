<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // We are allowing all guests to attempt registration.
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
            'user_login' => 'required|string|max:60|unique:users,user_login',
            'user_email' => 'required|string|email:filter|max:100|unique:users,user_email',
            'password' => 'required|string|min:6|confirmed',
            'display_name' => 'nullable|string|max:250',
        ];
    }
}
