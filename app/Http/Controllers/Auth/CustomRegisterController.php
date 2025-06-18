<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomRegisterController extends Controller
{
    $salt = Str::random(8);
    $password = $request->password . $salt;
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'salt' => $salt,
        'password' => Hash::make($password),
    ]);

}
