<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest; // Import the new request class
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \App\Http\Requests\Auth\RegisterRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        // The validation is now handled by RegisterRequest.
        // If validation fails, a response will be sent automatically.
        $user = $this->create($request->all());

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    protected function registered(Request $request, User $user)
    {
        if ($user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
            return response()->json(['status' => trans('verification.sent')]);
        }

        return response()->json(['status' => 'success', 'message' => 'Registration successful. Please log in.'], 201);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data): User
    {
        $nicename = Str::slug($data['user_login']);

        return User::create([
            'user_login' => $data['user_login'],
            'user_email' => $data['user_email'],
            'user_pass' => $data['password'], // The model will hash this automatically
            'user_nicename' => $nicename,
            'display_name' => $data['display_name'] ?? $data['user_login'],
            'user_registered' => Carbon::now(),
            'user_status' => 0, // Default status for a new user in WordPress
        ]);
    }
}
