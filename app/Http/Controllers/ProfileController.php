<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'nickname' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatar;
        }

        $user->nickname = $request->nickname;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->save();

        return back()->with('success', 'Profile updated.');
    }

    public function destroy()
    {
        auth()->user()->delete();
        return redirect('/')->with('success', 'Account deleted.');
    }
}
