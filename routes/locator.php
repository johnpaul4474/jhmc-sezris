<?php
use App\Http\Controllers\Locator\LocatorController;
use App\Http\Controllers\Locator\ApplicationController;
use App\Http\Controllers\Locator\ArticleDetailController;
use App\Http\Controllers\Locator\UploadController;


Route::get('/locator', [LocatorController::class, 'index'])->name('locators.index');
Route::resource('applications', ApplicationController::class);
Route::resource('articles', ArticleDetailController::class);
Route::resource('uploads', UploadController::class);