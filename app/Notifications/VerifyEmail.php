<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Auth\Notifications\VerifyEmail as Notification;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends Notification
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(), // <-- Corrected: Use 'id' and getKey() for the user's primary key
                // 'hash' will be automatically added by Laravel
            ]
        );

        // The frontend URL is expected, so we remove the '/api' prefix from the generated URL
        return str_replace('/api', '', $url);
    }
}
