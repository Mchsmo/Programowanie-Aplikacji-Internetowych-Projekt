<?php
 
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
 
// Przekierowanie głównej strony
Route::get('/', fn() => redirect()->route('login'));
 
// Trasy dla gości (niezalogowanych)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});
 
// Trasy dla zalogowanych
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
});