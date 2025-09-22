<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\ApiResponse;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    use RegistersUsers, ApiResponse;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * The user has been registered.
     */
    protected function registered(Request $request, User $user)
    {
        if ($user instanceof MustVerifyEmail) {
            return response()->json(['status' => trans('verification.sent')]);
        }

        $user->load('profile');

        // Fix: Combine user_name and profile into a single response array
        $response = [
            'user_name' => $user->user_name,
            'profile' => $user->profile,
        ];

        return $this->successResponse($response, 'User registered successfully.');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email:filter|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     */
    protected function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            // Fix: Generate a unique username
            $baseNickname = Str::slug(explode('@', $data['email'])[0]);
            $username = $baseNickname;
            $counter = 1;

            while (User::where('user_name', $username)->exists()) {
                $username = $baseNickname . '-' . $counter;
                $counter++;
            }

            $user = User::create([
                'user_name' => $username,
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);

            $user->profile()->create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
            ]);

            return $user;
        });
    }
}

