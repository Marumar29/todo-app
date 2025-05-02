<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    use RegistersUsers;
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Only letters and spaces
            'nickname' => ['nullable', 'string', 'max:255'], // Validation for nickname
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
    

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'nickname' => $data['nickname'], // Store the nickname if provided
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }


    public function register(RegisterRequest $request)
    {
        // Validate and create the user
        $data = $request->validated();
        $user = $this->create($data);
        
        // Log the user in automatically
        Auth::login($user);

        // Redirect to the profile page after successful registration
        return redirect()->route('profile.edit');
    }
}
