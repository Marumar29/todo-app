<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    // Edit profile form
    public function edit()
    {
        return view('profile.edit');
    }

    // Update profile data
    public function update(Request $request)
    {
        // Validation rules
        $validated = $request->validate([
            'nickname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|confirmed|min:6', // Password change (optional)
            'phone_number' => 'nullable|numeric',
            'city' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        // Get the authenticated user
        $user = auth()->user();
    
        // Update profile fields
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->city = $request->city;
    
        // Handle avatar upload if there's a file
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatar;
        }
    
        if ($request->filled('password')) {
            // Check current password
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
            } else {
                return redirect()->back()->withErrors(['current_password' => 'Current password does not match.']);
            }
        }

        $user->phone_number = $request->phone_number;

        // Save the user data
        $user->save();
    
        // Redirect back with success message
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }
    

    // Delete user profile
    public function destroy()
    {
        // Get the authenticated user
        $user = auth()->user();
        
        // Optionally, delete associated data like todos or files (if needed)
        // $user->todos()->delete();  // Example if you have related todos to delete
        
        // Delete the user
        $user->delete();
        
        // Log the user out
        Auth::logout();
        
        // Redirect to the homepage or login page
        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }
}
