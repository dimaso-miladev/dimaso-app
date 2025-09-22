<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Chúng ta để giá trị là true vì không cần kiểm tra quyền đặc biệt nào để thực hiện yêu cầu đăng nhập.
        // Middleware 'guest' đã xử lý việc người dùng đã đăng nhập hay chưa.
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
            'email' => 'required|string|email',
            'password' => 'required|string',
        ];
    }
}
