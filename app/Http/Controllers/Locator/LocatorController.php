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
        return Inertia::render('Locator/Index', [
            'applications' => $applications,
        ]);
       
    }
    public function show($id){
       return $id;
    }
    public function pendingList()
    {
     $appForm_number = ApplicationModel::where('user_id', Auth::id())->pluck('form_number');
     $applications = ApplicationForApproval::with([
        'application',
        'approverGroup.approvers'
    ])
    ->whereIn('form_number', $appForm_number)
    ->where('status', 'Pending')
    ->get();
         return Inertia::render('Locator/Application/Pending', [
            'applications' => $applications,
        ]);
    }

    public function approvedList(){
        $appIds = ApplicationModel::where('user_id', Auth::id())->pluck('id');
        $applications = ApplicationForApproval::with([
                        'application',
                        'approverGroup.approvers'
                        ])
                        ->whereIn('application_id', $appIds)
                        ->where('status', 'Approved')
                        ->get();

        return Inertia::render('Locator/Application/Approved', [
            'applications' => $applications,
        ]);
    }
}
