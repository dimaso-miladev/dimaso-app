<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable,
        HasFactory;

    /**
     * The table associated with the model.
     * For WordPress, this is often prefixed, e.g., 'wp_users'.
     * Assuming 'users' is correct or configured elsewhere.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key for the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

    /**
     * Indicates if the model should be timestamped.
     * WordPress uses user_registered instead of created_at/updated_at.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The attributes that should be hidden for arrays or JSON.
     *
     * @var array
     */
    protected $hidden = [
        'user_pass',
        'user_activation_key',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'user_registered' => 'datetime',
    ];

    /**
     * Automatically hash the user_pass when setting it.
     *
     * @param string $value
     * @return void
     */
    public function setUserPassAttribute($value)
    {
        // This will use Laravel's default hasher. Passwords created via the API
        // will be secure, but may not be recognized by a standard WordPress
        // install if it uses the older PHPass hashing algorithm.
        $this->attributes['user_pass'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    /**
     * Override method to get the password for authentication.
     * Laravel's Auth system will use the 'user_pass' column.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->user_pass;
    }

    /**
     * Get the email address for password resets.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->user_email;
    }

    /**
     * Override method to get the email for notifications.
     *
     * @param \Illuminate\Notifications\Notification $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->user_email;
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->user_email;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Relationship to usermeta table.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function usermeta()
    {
        return $this->hasMany(UserMeta::class, 'user_id', 'ID');
    }

    /**
     * Get a meta value from the usermeta table.
     *
     * @param string $key
     * @return mixed|null
     */
    public function getMetaValue(string $key)
    {
        $meta = $this->usermeta()->where('meta_key', $key)->first();
        return $meta ? $meta->meta_value : null;
    }
}
