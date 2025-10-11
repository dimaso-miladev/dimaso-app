<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        // We need to manually retrieve the user to check the password,
        // because WordPress uses a different hashing algorithm.
        $user = User::where('user_email', $credentials['email'])->first();

        if (! $user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Here, you would ideally use a package that can check WordPress hashes.
        // For this example, we'll assume a Laravel-compatible hash for any new users.
        // Note: This will NOT work for existing WordPress users with old passwords.
        if (! app('hash')->check($credentials['password'], $user->getAuthPassword())) {
             return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Check if the user has verified their email.
        if (! $user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Your email address is not verified.'], 403);
        }

        // Manually log in the user
        Auth::login($user);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
