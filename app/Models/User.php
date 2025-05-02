<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    // Allow these attributes to be mass assigned
    protected $fillable = [
        'name', 'email', 'password', 'nickname', 'avatar', 'phone_number', 'city', 'salt', 'mfa_enabled',
    ];

    // Hide sensitive information from model's array and JSON form
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast email_verified_at to a datetime instance
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Generate a random salt to be used when hashing the password.
     *
     * @return string
     */
    public static function generateSalt()
    {
        return Str::random(16); // 16-character random alphanumeric string (adjusted length)
    }

    /**
     * Hash the password with the salt using bcrypt.
     *
     * @param string $password
     * @param string $salt
     * @return string
     */
    public static function hashPasswordWithSalt($password, $salt)
    {
        return bcrypt($password . $salt); // bcrypt for secure password hashing
    }

    /**
     * Check if MFA (Multi-Factor Authentication) is enabled.
     *
     * @return bool
     */
    public function isMfaEnabled()
    {
        return $this->mfa_enabled; // Returns true if MFA is enabled, false otherwise
    }

    /**
     * Enable MFA for the user.
     */
    public function enableMfa()
    {
        $this->update(['mfa_enabled' => true]);
    }

    /**
     * Disable MFA for the user.
     */
    public function disableMfa()
    {
        $this->update(['mfa_enabled' => false]);
    }
}
