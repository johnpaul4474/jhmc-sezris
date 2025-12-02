<?php
use App\Http\Controllers\SEZAD\SezadManagerController;
use App\Http\Controllers\CCO\CcoController;
     Route::prefix('manager')->group(function () {
         Route::get('/manager', [SezadManagerController::class, 'index'])->name('sezad.manager.index');
         Route::get('/manager/{id}/show', [SezadManagerController::class, 'show'])->name('sezad.manager.show');
     })->name('manager');
   
