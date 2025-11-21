<?php
use App\Http\Controllers\CCO\CcoController;

Route::get('/cco', [CcoController::class, 'index'])->name('cco.index');