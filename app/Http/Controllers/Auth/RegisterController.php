<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $user = User::create([
            'user_login' => $data['user_login'],
            'user_pass' => $data['password'],
            'user_nicename' => Str::slug($data['user_login']),
            'user_email' => $data['user_email'],
            'user_registered' => now(),
            'display_name' => $data['display_name'],
            'user_status' => USER_STATUS_UNVERIFIED, // Use defined constant
        ]);

        event(new Registered($user));

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email to verify your account.'
        ], 201);
    }
}
