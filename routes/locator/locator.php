<?php
use App\Http\Controllers\Locator\LocatorController;
use App\Http\Controllers\Locator\ApplicationController;
use App\Http\Controllers\Locator\ArticleDetailController;
use App\Http\Controllers\Locator\UploadController;
use App\Http\Controllers\Locator\ApplicationForApprovalController;
use App\Helpers\AppConstants;
use App\Models\Locator\ApplicationModel;
use App\Models\User;


Route::get('/locator', [LocatorController::class, 'index'])->name('locators.index');
Route::resource('approval', ApplicationForApprovalController::class);
Route::group(['prefix' => 'loctr', 'middleware' => 'auth'], function () {
    //route for pending view
    Route::get('applications/pending', [LocatorController::class, 'pendingList'])->name('applications.pending');
    //Route for single pending view
    Route::get('applications/{id}/pending',[LocatorController::class, 'pendingShow'])->name('applications.pendingShow');
    //route for approved 
    Route::get('applications/{id}/approved', [LocatorController::class, 'approvedShow'])->name('applications.approvedShow');
    Route::get('applications/approved', [LocatorController::class, 'approvedList'])->name('applications.approved');
    //route for creating application (crud) 
    Route::resource('applications', ApplicationController::class);
    //route articles(crud) 
    Route::resource('articles', \App\Http\Controllers\Locator\ArticleDetailController::class);
    //route for uploads/attachment (crud) 
    Route::resource('uploads', UploadController::class);
    //route for declared vue and validity 
    Route::post('/applications/option-selection', [\App\Http\Controllers\Locator\ApplicationController::class, 'saveOptionSelection'])
    ->name('applications.option-selection');
});

//routes for application approve and return
Route::post('application-for-approval/{form_number}/approvers/{approverId}/approve', [ApplicationForApprovalController::class, 'approve'])
    ->name('application-for-approval.approve');

Route::post('application-for-approval/{id}/approvers/{approverId}/return', [ApplicationForApprovalController::class, 'returnApproval'])
    ->name('application-for-approval.return');

//how form meta works
Route::get('/test-meta', function(){
   $applicationTest = App\Models\Locator\ApplicationModel::find(15);
   $applicationTest->setMeta('processing', 'processing required.');
    $meta= $applicationTest->getMeta('processing');
dd($meta);

});
// Route::get('/app', function(){
//     $date= now();
//  $applications = ApplicationModel::where('user_id', Auth::id())
//     ->where('form_title', 'ATO')
//     ->where('status', 'Approved')
//     ->first();
//   $applications->setMeta('verified','ATOS123');   
//   $expiration= $applications->getMeta('Expiration');
//  $meta = $applications->meta;
//     dump($expiration === 'ATOS123' );
    
// });
// routes/web.php


