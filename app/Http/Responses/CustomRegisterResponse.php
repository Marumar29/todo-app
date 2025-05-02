<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse;

class CustomRegisterViewResponse implements RegisterResponse
{
    public function toResponse($request)
    {
        return redirect()->route('home'); // or 'dashboard' or any route name you want
    }
}
