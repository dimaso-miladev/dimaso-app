<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    use Notifiable,
        HasFactory;

    // ... (rest of the model remains the same)

    protected $table = 'users';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'user_login',
        'user_pass',
        'user_nicename',
        'user_email',
        'user_url',
        'user_registered',
        'user_status',
        'display_name',
    ];

    protected $hidden = [
        'user_pass',
        'user_activation_key',
    ];

    protected $casts = [
        'user_registered' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    public function setUserPassAttribute($value)
    {
        $this->attributes['user_pass'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }

    public function routeNotificationForMail($notification)
    {
        return $this->user_email;
    }

    public function getEmailForVerification()
    {
        return $this->user_email;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    // The sendEmailVerificationNotification method has been removed to allow Laravel's default to be used.

    public function usermeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'ID');
    }

    public function getMetaValue(string $key)
    {
        $meta = $this->usermeta()->where('meta_key', $key)->first();
        return $meta ? $meta->meta_value : null;
    }
}
