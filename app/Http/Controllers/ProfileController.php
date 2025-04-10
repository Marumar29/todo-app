<?php

public function edit()
{
    return view('profile.edit');
}

public function update(Request $request)
{
    $user = auth()->user();
    $user->nickname = $request->nickname;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    $user->city = $request->city;

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        $avatar = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatar;
    }

    $user->save();

    return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
}

public function destroy()
{
    auth()->user()->delete();
    return redirect('/login');
}

$request->validate([
    'nickname' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email,' . auth()->id(),
    'phone_number' => 'nullable|numeric',
    'city' => 'nullable|string|max:255',
    'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
]);

