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
use Illuminate\Support\Facades\Gate;


class LocatorController extends Controller
{
    public function index(){
        
       
  if (Gate::denies('access-locator')) {
            abort(403, 'Unauthorized');
        }
        $user = auth()->user();
       $AppForapprovals = $user->approvals;
       
         $applications = auth()->user()?->applications ?? [];
        return Inertia::render('Locator/Index', [
            'applications' => $AppForapprovals,
        ]);
    }
    
    public function show(String $id){


        if (Gate::denies('access-locator')) {
            abort(403, 'Unauthorized');
        }
       $application = ApplicationModel::with(['articleDetails', 'uploads', 'selections'])
    ->where('id', $id)
    ->first();
    
       return Inertia::render('Locator/View',[
        'app' => $application,
       ]);
    }
    
}
