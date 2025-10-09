<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;

class LocatorController extends Controller
{
    public function index(){
        $applications = ApplicationModel::all();
        
        return Inertia::render('Locator/Index', [
            'applications' => $applications,
        ]);
       
    }
    public function pendingList(){
        return Inertia::render('Locator/Application/Pending', []);
    }
    public function approvedList(){
        return Inertia::render('Locator/Application/Approved',[]);
    }
}
