<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Models\User; 


class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');  // Make sure this view exists: resources/views/auth/login.blade.php
    }
    
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
    
        // Find the user by email
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            // Concatenate the entered password with the stored salt
            $hashedPassword = Hash::make($request->password . $user->salt); 
    
            // Log the raw password and the hashed password for debugging
            Log::info('Attempting login:', [
                'entered_password' => $request->password,
                'salt' => $user->salt,
                'hashed_password' => $hashedPassword,
                'stored_password' => $user->password
            ]);
    
            // Check if the entered password hash matches the stored hash
            if (Hash::check($hashedPassword, $user->password)) {
                // Successful login
                Auth::login($user);
                $request->session()->regenerate();
                return redirect()->intended('/dashboard'); // Redirect after successful login
            }
        }
    
        // If login fails
        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->onlyInput('email');
    }
    
    
    
    
    

    public function logout(Request $request)
    {
        // Log the user out
        Auth::logout();

        // Invalidate the session and regenerate the token for security
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');  // Redirect to login after logout
    }
}
