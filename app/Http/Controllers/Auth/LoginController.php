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
        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_login';

        $credentials = [
            $loginField => $request->input('login'),
            'password' => $request->input('password')
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => [trans('auth.failed')],
            ]);
        }

        $user = auth('api')->user();

        // Check if the user's email is verified
        if (!$user->hasVerifiedEmail() || $user->user_status !== USER_STATUS_ACTIVE) {
            auth('api')->logout(); // Invalidate the token

            return response()->json(['message' => 'Your email address has not been verified.'], 403);
        }

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
        $user = auth('api')->user();
        
        $user->makeHidden('user_email', 'user_login', 'ID');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
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
