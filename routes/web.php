<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController; // Add this line to import ProfileController

Route::get('/', function () {
    return view('welcome'); // or redirect('/login') if you want
});

// Authentication routes
Auth::routes();

// Protected routes (for authenticated users)
Route::middleware(['auth'])->group(function () {
    // Todo routes
    Route::resource('todos', TodoController::class);
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Optional: redirect after login/register
Route::get('/home', function () {
    return redirect('/todos');
});
