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
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'], // Only letters and spaces
            'nickname' => ['nullable', 'string', 'max:255'], // Validation for nickname
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
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
