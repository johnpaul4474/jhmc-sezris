<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Locator\ApplicationForApproval;
use App\Helpers\AppConstants;
use App\Models\Locator\ApproverGroupApprover;
use App\Models\User;



class LocatorController extends Controller
{
    public function index(){
        
        $user = auth()->user();
 $isLocator =
            optional($user->details)->role_id == 3 &&
            optional($user->details)->permission_id == 2;
            if(!$isLocator){
        abort(401, 'Unauthorized.');
            }else{
       $AppForapprovals = $user->approvals;
       
         $applications = auth()->user()?->applications ?? [];
        return Inertia::render('Locator/Index', [
            'applications' => $AppForapprovals,
        ]);
    }
    }
    public function show(String $id){
       $application = ApplicationModel::with(['articleDetails', 'uploads', 'selections'])
    ->where('id', $id)
    ->first();
    
       return Inertia::render('Locator/View',[
        'app' => $application,
       ]);
    }
    
}
