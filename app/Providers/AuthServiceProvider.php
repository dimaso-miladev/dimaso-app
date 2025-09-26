<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\URL;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Customize the email verification URL.
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $params = [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ];

            // Create a temporary signed URL.
            // It will expire after the duration defined in the constant.
            $url = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(config('constants.verification.link_expiration_minutes')),
                $params
            );

            // Keep the /api prefix so the verification hits the API route directly.
            return $url;
        });
    }
}
