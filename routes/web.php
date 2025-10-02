<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Users routes
Route::get('users', function () {
    return Inertia::render('users/UsersDashboard');
})->middleware(['auth', 'verified'])->name('usersDashboard');

//SEZAD routes
Route::get('sezad', function () {
    return Inertia::render('sezad/SezadDashboard');
})->middleware(['auth', 'verified'])->name('sezadDashboard');

//BDD routes
Route::get('bdd', function () {
    return Inertia::render('BddDashboard');
})->middleware(['auth', 'verified'])->name('bddDashboard');

//address
Route::get('address', function () {
    return Inertia::render('users/Address');
})->middleware(['auth', 'verified'])->name('usersAddress');

Route::fallback(function () {
    return Inertia::render('NotFound');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/locator.php';
require __DIR__.'/notification.php';
