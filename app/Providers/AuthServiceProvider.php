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

        // Use the default VerifyEmail notification but customize the URL generation
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $params = [
                'id' => $notifiable->getKey(), // Use the user's primary key
                'hash' => sha1($notifiable->getEmailForVerification()), // Generate the hash
            ];

            // Generate the signed URL for the frontend
            $url = URL::temporarySignedRoute('verification.verify', now()->addMinutes(60), $params);

            // The frontend does not use the /api prefix, so we remove it.
            return str_replace('/api', '', $url);
        });
    }
}
