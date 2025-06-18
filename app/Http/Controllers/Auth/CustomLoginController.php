<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class CustomLoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        // Manually append salt and check password
        $inputPassword = $request->password . $user->salt;

        if (Hash::check($inputPassword, $user->password)) {
            Auth::login($user);
            return redirect()->intended('/todo'); // or your role-based logic here
        } else {
            return back()->withErrors(['password' => 'Invalid credentials.']);
        }
    }
}
