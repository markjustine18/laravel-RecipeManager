<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', [AuthController::class, 'showLoginForm']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.auth');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.auth');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout.auth');


use App\Http\Controllers\RecipeController;

Route::get('/index', [RecipeController::class, 'index'])->name('recipes.index');
Route::get('/index1', [RecipeController::class, 'index1'])->name('recipes.guest');

Route::get('/add', [RecipeController::class, 'create'])->name('recipes.create');
Route::post('/add', [RecipeController::class, 'create'])->name('recipes.create');

Route::put('recipes/{id}', [RecipeController::class, 'update'])->name('recipes.update');
Route::get('recipes/{id}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');

Route::get('/edit/{id}', [RecipeController::class, 'edit'])->name('recipes.edit');
Route::post('/edit/{id}', [RecipeController::class, 'edit'])->name('recipes.edit');

Route::get('/delete/{id}', [RecipeController::class, 'delete'])->name('recipes.delete');

Route::get('/recipes/{recipe_id}', [RecipeController::class, 'show'])->name('recipes.view');

use App\Http\Controllers\ReviewController;

Route::post('/reviews}', [ReviewController::class, 'store'])->name('reviews.store');
Route::get('/reviews/create}', [ReviewController::class, 'create'])->name('reviews.create');


use App\Http\Controllers\CategoriesController;

Route::get('/categories', [CategoriesController::class, 'index'])->name('categories');
Route::post('/categories', [CategoriesController::class, 'index']);

use App\Http\Controllers\FavoriteController;

Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
Route::post('/add-favorites', [FavoriteController::class, 'add'])->name('favorites.add');
Route::post('/remove-favorites', [FavoriteController::class, 'remove'])->name('favorites.remove');

use App\Http\Controllers\ProfileController;

Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
