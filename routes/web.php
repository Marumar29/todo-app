<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController; // Already imported
use App\Http\Controllers\TwoFactorController; // Add the TwoFactorController

// Landing page or default route
Route::get('/', function () {
    return view('welcome'); // Or redirect('/login') if you want to show login first
});

// Protected routes for authenticated users
Route::middleware(['auth'])->group(function () {
    // Todo routes
    Route::resource('todos', TodoController::class);
    
    // Profile routes (view, update, and delete user profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Two-Factor Authentication routes
    Route::get('/two-factor', [TwoFactorController::class, 'show'])->name('two-factor.index'); // Show 2FA page
    Route::post('/two-factor-verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify'); // Verify 2FA code
});

// Redirect after login/register
Route::get('/home', function () {
    return redirect('/profile'); // Redirect to profile page after login or registration
})->name('home');

// Authentication Routes (login and register)
// Ensure these are using Fortify or your custom controllers if needed
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

