<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $credentials = [
            'user_login' => $request->user_login,
            'password' => $request->password
        ];

        // Sử dụng trực tiếp phương thức `attempt` của guard `api`.
        // Nó sẽ tự động kiểm tra credentials dựa trên provider `users` đã được cấu hình
        // và trả về token nếu thành công.
        if (! $token = auth('api')->attempt($credentials)) {
            // Nếu attempt thất bại, trả về lỗi.
            throw ValidationException::withMessages([
                'user_login' => [trans('auth.failed')],
            ]);
        }

        // Nếu thành công, trả về response chứa token.
        return $this->sendLoginResponse($token);
    }

    /**
     * Gửi response sau khi người dùng đã được xác thực.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()
        ]);
    }

    /**
     * Đăng xuất người dùng (vô hiệu hóa token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
