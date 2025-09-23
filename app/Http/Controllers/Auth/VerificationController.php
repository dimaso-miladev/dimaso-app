<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. The `VerifiesEmails`
    | trait contains the necessary logic.
    |
    */

    use VerifiesEmails;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Users must be authenticated to resend a verification email.
        $this->middleware('auth:api')->only('resend');

        // The verification link must have a valid signature.
        $this->middleware('signed')->only('verify');

        // Throttle requests to prevent spam.
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthorizationException
     */
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        // Verify that the hash in the URL matches the one generated for the user's email.
        // This prevents URL tampering.
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            throw new AuthorizationException;
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        // Mark the email as verified and activate the user account.
        if ($user->markEmailAsVerified()) {
            $user->user_status = config('constants.user.status_active'); // Use new config value
            $user->save();
            
            // Dispatch the Verified event.
            event(new Verified($user));
        }

        return response()->json(['message' => 'Email successfully verified.']);
    }

    /**
     * Resend the email verification notification.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent.']);
    }
}
