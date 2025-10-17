<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Locator\ApplicationForApproval;



class LocatorController extends Controller
{
    public function index(){
        $applications = auth()->user()->applications;
        if($applications){
            return Inertia::render('Locator/Index', [
                'applications' => $applications,
        ]);
    }else{
        return Inertia::render('Locator/Index',[]);
    }
       
    }
    public function show($id){
       return $id;
       
    }
    public function pendingList()
    {
        $applications = ApplicationModel::where('user_id', Auth::id())
    ->where('status', 'pending')
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
    public function myApprovals()
{
    $user = Auth::user();

    // Get all application_for_approval records where the user's approver group contains this user
    $applications = ApplicationForApproval::whereHas('approverGroup.approvers', function ($query) use ($user) {
        $query->where('users.id', 2);
    })
    ->with(['approverGroup.approvers', 'application'])
    ->get();
      dd($applications);
    // return Inertia::render('Locator/Applications/MyApprovals', [
    //     'applications' => $applications,
    // ]);
}
}
