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
use Illuminate\Support\Facades\Auth;
use App\Helpers\UserHelper;
use App\Http\Controllers\BDD\BddController;
use App\Http\Controllers\SEZAD\SEZADController;

// Route::get('/oauth/google', [GoogleOAuthController::class, 'redirectToGoogle']);
// Route::get('/oauth/google/callback', [GoogleOAuthController::class, 'handleGoogleCallback']);
// Route::get('/send-test-email', [UserDetailsController::class, 'sendTestEmail']);

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
/** @var \App\Models\User|null $user */
// Route::get('dashboard', function () {
//     $user = UserHelper::loadUserWithDetails();
//     return Inertia::render('Dashboard', [
//         'auth' => ['user' => $user],
//     ]);
// })
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

//Users routes
Route::get('/users', [UserDetailsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('usersDashboard');

Route::get('/users/list', [UserDetailsController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('users.list');

Route::post('/user/addUser', [UserDetailsController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('userDetails.store');;



//SEZAD routes
Route::middleware(['auth', 'verified', 'role.access'])->group(function () {
    /** @var \App\Models\User|null $user */
    Route::get('dashboard', function () {
        $user = UserHelper::loadUserWithDetails();
        return Inertia::render('Dashboard', [
            'auth' => ['user' => $user],
        ]);
    })
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::get('/sezad', [SEZADController::class, 'index'])->name('sezadDashboard');
    // add users and other routes
});

// Route::get('/sezad', [SEZADController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('sezadDashboard');

//BDD routes
Route::get('/bdd', [BDDController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('bddDashboard');

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

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
