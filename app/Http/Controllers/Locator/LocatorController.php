<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Locator\ApplicationForApproval;
use App\Helpers\AppConstants;



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
         $user = Auth::user();
       $currentUserApplication = ApplicationModel::where('user_id',$user->id)->first();
        $approvers = ApplicationForApproval::whereHas('approverGroup.approvers', function ($query) use ($user,$currentUserApplication) {
                                                                     $query->where('application_id', $currentUserApplication->id);
                                                                    })
    ->with(['approverGroup.approvers', 'application'])
    ->get();
        $applications = ApplicationModel::where('user_id', Auth::id())
    ->where('status', AppConstants::STATUS_PENDING)
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
