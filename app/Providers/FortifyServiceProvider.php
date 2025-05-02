<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the CreatesNewUsers interface to your custom CreateNewUser action
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

    }

    public function boot()
    {
        // Register the custom Fortify actions
        Fortify::createUsersUsing(CreateNewUser::class); // Use your CreateNewUser action for registration
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Rate limiting for login attempts
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(3)->by($request->email . '|' . $request->ip());
        });

    }
}
