<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;

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
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    // Cast email_verified_at to a datetime instance
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * Generate a random salt to be used when hashing the password.
     *
     * @return string
     */
    public static function generateSalt()
    {
        return Str::random(32); // 32-character random alphanumeric string
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
        return Hash::make($password . $salt); // bcrypt for secure password hashing with a salt
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

    /**
     * Handle the password setting with hashing and salt.
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        // Generate the salt
        $salt = self::generateSalt();

        // Hash the password with the salt and assign it to the password attribute
        $this->attributes['salt'] = $salt;
        $this->attributes['password'] = self::hashPasswordWithSalt($password, $salt);
    }

    /**
     * Ensure that the salt is always set when updating the password.
     *
     * @param string $password
     * @return void
     */
    public function updatePassword($password)
    {
        $this->setPasswordAttribute($password);
        $this->save();
    }

    /**
     * Enable the user’s MFA and confirm that it has been enabled.
     */
    public function enableMfaAndConfirm()
    {
        $this->enableMfa();
        $this->update(['two_factor_confirmed_at' => now()]);
    }

    /**
     * Check if MFA has been confirmed.
     *
     * @return bool
     */
    public function isMfaConfirmed()
    {
        return !is_null($this->two_factor_confirmed_at); // Returns true if MFA has been confirmed, false otherwise
    }
}
