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
use App\Http\Responses\CustomRegisterResponse;
use App\Http\Responses\CustomRegisterViewResponse;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\RegisterViewResponse;

class FortifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind the CreatesNewUsers interface to your custom CreateNewUser action
        $this->app->singleton(CreatesNewUsers::class, CreateNewUser::class);

        $this->app->singleton(RegisterResponse::class, CustomRegisterResponse::class);
        $this->app->singleton(RegisterViewResponse::class, CustomRegisterViewResponse::class);
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

        // Register custom register view response (if you need a custom registration view)
        Fortify::registerView(fn () => app(CustomRegisterViewResponse::class)->toResponse(request()));

        // Register MFA challenge view
        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        // Enable email verification for MFA
        Fortify::emailVerification();

        // Enable built-in Fortify 2FA
        Fortify::enableTwoFactorAuthentication();

        // Custom authentication logic to handle MFA (email)
        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Models\User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password . $user->salt, $user->password)) {
                // Generate and send MFA code via email
                $code = rand(100000, 999999); // 6-digit MFA code

                // Send code to user email
                Mail::to($user->email)->send(new \App\Mail\TwoFactorCodeMail($code));

                // Store MFA code in session for verification
                session(['mfa_code' => $code]);

                return $user;
            }

            return null; // Return null if authentication fails
        });
    }
}
