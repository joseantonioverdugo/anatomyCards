<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FlashcardController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('flashcards', function () {
    return Inertia::render('FlashCards');
})->middleware(['auth', 'verified'])->name('flashcards');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::resource('users', UserController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('flashcards', FlashcardController::class)->only(['index', 'store', 'show', 'destroy']);
    Route::match(['put', 'patch', 'post'], 'flashcards/{flashcard}', [FlashcardController::class, 'update'])->name('flashcards.update');
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('subcategories', SubcategoryController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('options', OptionController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
