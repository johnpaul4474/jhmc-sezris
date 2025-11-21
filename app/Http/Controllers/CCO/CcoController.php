<?php

namespace App\Http\Controllers\CCO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;

class CcoController extends Controller
{
    public function index(){
        $applications = ApproverGroupApprover::with('application')
    ->where('approver_id', auth()->id())
    ->get();
        return Inertia::render('CCO/Index',[
             'applications'=> $applications,
        ]);
    }
}
