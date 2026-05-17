<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RatingController;
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

    Route::get('/recipes/favorites',  [RecipeController::class, 'favorites'])->name('recipes.favorites');
    Route::get('/recipes/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.my-recipes');
    
    Route::resource('recipes', RecipeController::class);
    Route::get('/recipes/{recipe}', [RecipeController::class, 'show'])->name('recipes.show');
    
    Route::post('/recipes/{recipe}/favorite-toggle', [RecipeController::class, 'toggleFavorite'])->name('recipes.favorite.toggle');
    Route::post('/recipes/{recipe}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/recipes/{recipe}/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::post('/recipes/{recipe}/favorite-notes', [RecipeController::class, 'updateFavoriteNotes'])->name('recipes.favorite.notes');
    
    Route::get('/profile/edit',       [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',          [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});