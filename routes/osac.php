<?php
use App\Http\Controllers\OSAC\OsacController;

Route::get('/osac', [OsacController::class, 'index'])->name('osac.index');
Route::get('/osac/apply', [OsacController::class, 'create'])->name('osac.create');