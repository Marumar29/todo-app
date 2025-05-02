<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController; // Already imported

// Landing page or default route
Route::get('/', function () {
    return view('welcome'); // Or redirect('/login') if you want to show login first
});

// Authentication routes (Fortify automatically handles routes, so no need to manually define them here)
// Auth::routes();  // REMOVE this line if you're using Fortify

// Protected routes for authenticated users
Route::middleware(['auth'])->group(function () {
    // Todo routes
    Route::resource('todos', TodoController::class);
    
    // Profile routes (view, update, and delete user profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Optional: Redirect after login/register
Route::get('/home', function () {
    return redirect('/profile'); // Redirect to profile page after login or registration
})->name('home');

Route::post('/two-factor-verify', [TwoFactorController::class, 'verify'])->name('two-factor.verify');
