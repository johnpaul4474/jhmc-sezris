<?php
use App\Http\Controllers\Locator\LocatorController;


Route::get('/locator', [LocatorController::class, 'index'])->name('locators.index');