<?php
use App\Http\Controllers\Locator\LocatorController;
use App\Http\Controllers\Locator\ApplicationController;
use App\Http\Controllers\Locator\ArticleDetailController;
use App\Http\Controllers\Locator\UploadController;


Route::get('/locator', [LocatorController::class, 'index'])->name('locators.index');

Route::group(['prefix' => 'loctr', 'middleware' => 'auth'], function () {
     Route::get('applications/pending', [ApplicationController::class, 'pendingList'])->name('applications.pending');
     Route::get('applications/approved', [ApplicationController::class, 'approvedList'])->name('applications.approved');
     Route::resource('applications', ApplicationController::class);
     Route::resource('articles', ArticleDetailController::class);
     Route::resource('uploads', UploadController::class);
     
});
Route::get('/test-route', function (){
    abort(404);
}); 
