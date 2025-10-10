<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;

class LocatorController extends Controller
{
    public function index(){
        $applications = auth()->user()->applications()->with('user')->get();
        return Inertia::render('Locator/Index', [
            'applications' => $applications,
        ]);
       
    }
    public function show($id){
       return $id;
    }
    public function pendingList()
    {
        $applications = ApplicationModel::where('status', 'pending')->get();
        
        return Inertia::render('Locator/Application/Pending', [
            'applications' => $applications,
        ]);
    }

    public function approvedList(){
        $applications = ApplicationModel::where('status', 'approved')
         ->where('control_number', '!=', '')
        ->get();

        return Inertia::render('Locator/Application/Approved', [
            'applications' => $applications,
        ]);
    }
}
