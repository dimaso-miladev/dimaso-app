<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Handle a registration request for the application.
     *
     * @param  RegisterRequest  $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::create([
            'user_login' => $data['user_login'],
            'user_pass' => $data['password'],       // The mutator in the User model will hash this.
            'user_nicename' => Str::slug($data['user_login']), // Create a URL-friendly nicename.
            'user_email' => $data['user_email'],
            'user_registered' => now(),
            'display_name' => $data['display_name'],
            'user_status' => config('constants.user.status_unverified'), // Default status is unverified.
        ]);

        // Dispatch the Registered event.
        // Laravel's listener will automatically send the verification email
        // because the User model implements the MustVerifyEmail interface.
        event(new Registered($user));

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email to verify your account.'
        ], 201);
    }
}
