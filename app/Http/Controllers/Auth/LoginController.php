<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new LoginController instance.
     *
     * The 'auth:api' middleware is applied to all methods except for 'login'.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  LoginRequest  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function login(LoginRequest $request)
    {
        // Determine if the user is logging in with an email or a username.
        // FILTER_VALIDATE_EMAIL is a native PHP constant.
        $loginField = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'user_email' : 'user_login';

        $credentials = [
            $loginField => $request->input('login'),
            'password' => $request->input('password')
        ];

        // Attempt to authenticate the user with the provided credentials.
        if (!$token = auth('api')->attempt($credentials)) {
            throw ValidationException::withMessages([
                'login' => [trans('auth.failed')],
            ]);
        }

        $user = auth('api')->user();

        // Block login if the email is not verified or the account is not active.
        if (!$user->hasVerifiedEmail() || $user->user_status !== config('constants.user.status_active')) {
            auth('api')->logout(); // Invalidate the token immediately.

            return response()->json(['message' => 'Your email address has not been verified.'], 403);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        $user = auth('api')->user();
        
        // Hide unnecessary fields before returning the user object.
        $user->makeHidden('user_email', 'user_login', 'ID');

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }
}
