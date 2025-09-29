<?php
use App\Http\Controllers\Locator\LocatorController;
use App\Http\Controllers\Locator\ApplicationController;
use App\Http\Controllers\Locator\ArticleController;


Route::get('/locator', [LocatorController::class, 'index'])->name('locators.index');
Route::resource('applications', ApplicationController::class);
Route::resource('articles', ArticleController::class);