<?php

use App\Models\UserDetails;
use App\Http\Controllers\Users\UserDetailsController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Utilities\LookupController;
use App\Services\GmailService;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Mail\ChangePasswordMail;


// Route::get('/oauth/google', [GoogleOAuthController::class, 'redirectToGoogle']);
// Route::get('/oauth/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback']);
// Route::get('/send-test-email', [UserDetailsController::class, 'sendTestEmail']);

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Users routes
Route::get('/users', [UserDetailsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('usersDashboard');
Route::get('/users/list', [UserDetailsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('users.list');
Route::post('/user/addUser', [UserDetailsController::class, 'store'])->middleware(['auth', 'verified'])->name('userDetails.store');;



//SEZAD routes
Route::get('sezad', function () {
    return Inertia::render('sezad/SezadDashboard');
})->middleware(['auth', 'verified'])->name('sezadDashboard');

//BDD routes
Route::get('bdd', function () {
    return Inertia::render('bdd/BddDashboard');
})->middleware(['auth', 'verified'])->name('bddDashboard');

//address
Route::get('address', function () {
    return Inertia::render('users/Address');
})->middleware(['auth', 'verified'])->name('usersAddress');

//Utilities routes
Route::get('/departments', [LookupController::class, 'departments'])->middleware(['auth', 'verified'])->name('departments');
Route::get('/divisions', [LookupController::class, 'divisions'])->middleware(['auth', 'verified'])->name('divisions');
Route::get('/roles', [LookupController::class, 'roles'])->middleware(['auth', 'verified'])->name('roles');
Route::get('/permissions', [LookupController::class, 'permissions'])->middleware(['auth', 'verified'])->name('permissions');


//Email
Route::get('/sendChangePassword', [UserDetailsController::class, 'sendChangePassword'])->middleware(['auth', 'verified'])->name('sendChangePassword');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
