<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest; // 1. Import LoginRequest
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(LoginRequest $request) // 2. Sử dụng LoginRequest tại đây
    {
        // Khối validate đã được loại bỏ, vì LoginRequest đã xử lý việc này.

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        $passwordMatches = false;

        $isOldMd5Password = strlen($user->password) === 32 && ctype_xdigit($user->password) && !str_starts_with($user->password, '$2y$');

        if ($isOldMd5Password) {
            if (md5($request->password) === $user->password) {
                $user->password = bcrypt($request->password);
                $user->save();
                $passwordMatches = true;
            }
        } else {
            if (Hash::check($request->password, $user->password)) {
                $passwordMatches = true;
            }
        }

        if (!$passwordMatches) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        if (!$token = auth('api')->login($user)) {
            throw ValidationException::withMessages([
                'email' => [trans('auth.failed')],
            ]);
        }

        return $this->sendLoginResponse($token);
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function username(): string
    {
        return 'email';
    }

    protected function sendLoginResponse(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user()->load('profile')
        ]);
    }
}
