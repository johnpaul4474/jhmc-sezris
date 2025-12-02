<?php
use App\Http\Controllers\SEZAD\SezadManagerController;
use App\Http\Controllers\CCO\CcoController;
     Route::prefix('manager')->group(function () {

    Route::get('/', [OsacController::class, 'index'])->name('osac.index');

    Route::get('/apply', [OsacController::class, 'create'])->name('osac.create');

    Route::get('/{id}/show', [OsacController::class, 'show'])->name('osac.show');

})->name('osac');
Route::get('/create2', [ATOController::class, 'CreateApp'])->name('app.create2');

    Route::get('/manager', [SezadManagerController::class, 'index'])->name('sezad.manager.index');
    Route::get('/manager/{id}/show', [SezadManagerController::class, 'show'])->name('sezad.manager.show');
