<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;
use Illuminate\Support\Facades\Auth;



class LocatorController extends Controller
{
    public function index(){
        $applications = auth()->user()->applications;
        if($applications){
        return Inertia::render('Locator/Index', [
            'applications' => $applications,
        ]);
    }else{
        return Inertia::render('Locator/Index', []);
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
}
