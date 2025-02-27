<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('flashcards', function () {
    return Inertia::render('FlashCards');
})->middleware(['auth', 'verified'])->name('flashcards');

Route::get('categories', function () {
    return Inertia::render('Categories');
})->middleware(['auth', 'verified'])->name('categories');

Route::get('subcategories', function () {
    return Inertia::render('Subcategories');
})->middleware(['auth', 'verified'])->name('subcategories');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
