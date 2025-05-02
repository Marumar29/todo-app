<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        // Ensure that only guests can access login-related actions
        $this->middleware('guest')->except('logout');
        // Ensure that authenticated users can only log out
        $this->middleware('auth')->only('logout');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        // Find the user by email
        $user = User::where('email', $credentials['email'])->first();

        if ($user) {
            // Combine the password with the stored salt
            $saltedPassword = $credentials['password'] . $user->salt;

            // Check if the password is valid
            if (Hash::check($saltedPassword, $user->password)) {
                // Check if MFA is enabled for the user
                if ($user->mfa_enabled) {
                    // Redirect to MFA verification page
                    session(['mfa' => true, 'user_id' => $user->id]);
                    return redirect()->route('mfa.verify');
                }

                // Log the user in and redirect to the intended route
                auth()->login($user);
                return redirect()->intended(route('profile.edit'));
            }
        }

        // If credentials are incorrect, show error
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // This method is called after a successful login (if MFA is not enabled)
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('profile.edit');
    }

    public function logout(Request $request)
    {
        // Logout the user, invalidate the session, and regenerate the CSRF token
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home
        return redirect('/');
    }
}
