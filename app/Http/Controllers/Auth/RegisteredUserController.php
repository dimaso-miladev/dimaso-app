<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:250'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users,user_email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validated = $validator->validated();

        $user = User::create([
            'display_name' => $validated['name'],
            'user_login' => $validated['email'], // Using email as login for simplicity
            'user_email' => $validated['email'],
            'user_pass' => Hash::make($validated['password']),
            'user_nicename' => $validated['name'],
            'user_registered' => now(),
        ]);

        event(new Registered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful. Please check your email to verify your account.',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        return response()->json(['message' => 'Email successfully verified.']);
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification link sent!']);
    }
}
