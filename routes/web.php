<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('users', function () {
    return Inertia::render('Users');
})->middleware(['auth', 'verified'])->name('users');

Route::get('flashcards', function () {
    return Inertia::render('FlashCards');
})->middleware(['auth', 'verified'])->name('flashcards');

Route::get('categories', function () {
    return Inertia::render('Categories');
})->middleware(['auth', 'verified'])->name('categories');

Route::get('subcategories', function () {
    return Inertia::render('Subcategories');
})->middleware(['auth', 'verified'])->name('subcategories');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
