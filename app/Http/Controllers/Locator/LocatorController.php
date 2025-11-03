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
use App\Model\User;



class LocatorController extends Controller
{
    public function index(){
         abort_unless(auth()->check(), 403, 'Unauthorized');
         $applications = auth()->user()?->applications ?? [];
         //abort_unless(!auth()->id(), 403);
        return Inertia::render('Locator/Index', [
            'applications' => $applications,
        ]);
       
    }
    public function show($id){
       return $id;
    }
    public function pendingList()
    {
        
$applications = ApplicationForApproval::with([
        'application',
        'approverGroup.approvers'
    ])
    ->where('status','Pending')
    //->assignedToUser(Auth::user())
    ->get();

         return Inertia::render('Locator/Application/Pending', [
            'applications' => $applications,
        ]);
    }

    public function approvedList(){
        $applications = ApplicationModel::where('user_id', Auth::id())
    ->where('status', 'approved')
    ->get();

        return Inertia::render('Locator/Application/Approved', [
            'applications' => $applications,
        ]);
    }
}
