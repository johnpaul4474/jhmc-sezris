<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Helpers\UserHelper;
use App\Models\UserDetails;
use App\Http\Controllers\Users\UserDetailsController;
use App\Http\Controllers\Utilities\LookupController;
use App\Http\Controllers\BDD\BddController;

use App\Http\Controllers\SEZAD\SEZADController;
use App\Http\Controllers\Auth\GoogleOAuthController;
use App\Mail\ChangePasswordMail;
use App\Services\GmailService;
use App\Http\Controllers\SEZAD\Clearances\BringInController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Keep all URL names intact. Organized by section for clarity.
|
*/

// 🔹 Public Route
Route::get('/', fn() => Inertia::render('Welcome'))->name('home');

// 🔹 Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('dashboard', function () {
        $user = UserHelper::loadUserWithDetails();
        return Inertia::render('Dashboard', ['auth' => ['user' => $user]]);
    })->name('dashboard');



    /*
    |--------------------------------------------------------------------------
    | SEZAD
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', 'verified', 'role.access'])->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::prefix('users')->group(function () {
            Route::get('/', [UserDetailsController::class, 'index'])->name('usersDashboard');
            Route::get('/list', [UserDetailsController::class, 'index'])->name('users.list');
            Route::post('/addUser', [UserDetailsController::class, 'store'])->name('userDetails.store');
        });
        /*
        |--------------------------------------------------------------------------
        | BDD
        |--------------------------------------------------------------------------
        */
        Route::get('/bdd', [BDDController::class, 'index'])->name('bddDashboard');
        Route::prefix('sezad')->group(function () {
            Route::get('/', [SEZADController::class, 'index'])->name('sezadDashboard');

            Route::prefix('clearances')->group(function () {
                Route::get('/bring-in', [BringInController::class, 'index'])->name('bringInclearance');
            });
        });
    });

    

    /*
    |--------------------------------------------------------------------------
    | Address
    |--------------------------------------------------------------------------
    */
    Route::get('address', fn() => Inertia::render('users/Address'))->name('usersAddress');

    /*
    |--------------------------------------------------------------------------
    | Utilities
    |--------------------------------------------------------------------------
    */
    Route::get('/departments', [LookupController::class, 'departments'])->name('departments');
    Route::get('/divisions', [LookupController::class, 'divisions'])->name('divisions');
    Route::get('/roles', [LookupController::class, 'roles'])->name('roles');
    Route::get('/permissions', [LookupController::class, 'permissions'])->name('permissions');

    /*
    |--------------------------------------------------------------------------
    | Email
    |--------------------------------------------------------------------------
    */
    Route::get('/sendChangePassword', [UserDetailsController::class, 'sendChangePassword'])->name('sendChangePassword');
});

// 🔹 Include other route files
require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/locator/locator.php';
require __DIR__ . '/locator/notification.php';
require __DIR__ . '/osac.php';
require __DIR__ .'/Cco.php';
require __DIR__ .'/SezadManager.php';
