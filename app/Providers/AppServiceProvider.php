<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\RegisterViewResponse as RegisterViewResponseContract;
use App\Http\Responses\CustomRegisterViewResponse;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Bind CustomRegisterViewResponse to RegisterViewResponseContract
        $this->app->bind(RegisterViewResponseContract::class, CustomRegisterViewResponse::class);
    }

    public function boot()
    {
        //
    }
}
