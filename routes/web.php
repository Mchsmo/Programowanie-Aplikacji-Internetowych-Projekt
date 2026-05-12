<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('login'));

// Goście
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Zalogowani
Route::middleware('auth')->group(function () {
    Route::get('/dashboard',          [AuthController::class,   'dashboard'])->name('dashboard');
    Route::post('/logout',            [AuthController::class,   'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::resource('recipes', RecipeController::class);
    });
    Route::get('/profile/edit',       [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});