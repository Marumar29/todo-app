<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        // If you don't need any special permission check, return true
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z]+$/'], // Only letters
            'nickname' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9 ]+$/'], // Letters, numbers, spaces
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // At least 8 characters and confirmation
        ];
    }

    public function messages()
    {
        return [
            'name.regex' => 'Name can only contain letters.',
            'nickname.regex' => 'Nickname can only contain letters, numbers, and spaces.',
        ];
    }
}
