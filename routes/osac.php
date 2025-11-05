<?php
use App\Http\Controllers\OSAC\OsacController;

Route::get('/osac', [OsacController::class, 'index'])->name('osac.index');