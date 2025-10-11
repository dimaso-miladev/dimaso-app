<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable, HasRoles;

  /**
   * The table associated with the model.
   * Laravel will automatically use the "dimaso_" prefix.
   * @var string
   */
  protected $table = 'users';

  /**
   * The primary key associated with the table.
   *
   * @var string
   */
  protected $primaryKey = 'ID';

  /**
   * Indicates if the model should be timestamped.
   * WordPress uses `user_registered` but not `updated_at`.
   *
   * @var bool
   */
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'user_login',
    'user_pass',
    'user_nicename',
    'user_email',
    'user_url',
    'user_registered',
    'display_name',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'user_pass',
    'user_activation_key', // Hidden by default for security
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'user_registered' => 'datetime',
    'email_verified_at' => 'datetime',
  ];

  /**
   * Get the password for the user.
   * This tells Laravel's Auth system which column to use for the password.
   *
   * @return string
   */
  public function getAuthPassword()
  {
      return $this->user_pass;
  }

  /**
   * Get the name of the "remember me" token column.
   * We are disabling it because the column doesn't exist in WordPress's table.
   *
   * @return string
   */
  public function getRememberTokenName()
  {
      return '';
  }
}
