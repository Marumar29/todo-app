<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserRole;

class CustomRegisterController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validate input
        $request->validate([
            'name' => 'required|string|regex:/^[A-Za-z\s]+$/',
            'nickname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string',
            'city' => 'required|string',
            'role_name' => 'required|in:user,admin', // basic roles
        ]);

        // 2. Generate salt
        $salt = Str::random(8);

        // 3. Hash password with salt
        $passwordWithSalt = $request->password . $salt;
        $hashedPassword = Hash::make($passwordWithSalt);

        // 4. Create user
        $user = User::create([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'email' => $request->email,
            'password' => $hashedPassword,
            'salt' => $salt,
            'phone' => $request->phone,
            'city' => $request->city,
        ]);

        // 5. Assign role
        UserRole::create([
            'user_id' => $user->id,
            'role_name' => $request->role_name,
            'description' => $request->role_name === 'admin' ? 'Administrator' : 'Standard user',
        ]);

        // 6. Auto-login user
        Auth::login($user);

        // 7. Redirect based on role
        if ($request->role_name === 'admin') {
            return redirect('/admin-dashboard');
        } else {
            return redirect('/todo');
        }
    }
}
