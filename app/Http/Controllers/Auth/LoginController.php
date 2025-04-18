<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;  // Import Request class

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        
        if (Auth::attempt($credentials)) {
            return redirect()->intended(route('profile.edit')); // Directly redirect to profile page
        }
        
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }
    
    // This method is called after a successful login
    protected function authenticated(Request $request, $user)
    {
        // Redirect to the profile edit page after login
        return redirect()->route('profile.edit');
    }
}
