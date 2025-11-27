<?php

namespace App\Http\Controllers\CCO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CcoController extends Controller
{   
    
    public function index(){
        
            if (Gate::denies('access-cco')) {
            abort(403, 'Unauthorized');
        }
                $applications = ApproverGroupApprover::with('application')
                            ->where('approver_id', auth()->id())
                            ->get();
                                return Inertia::render('CCO/Index',[
                                    'applications'=> $applications,
                                ]);
            
       
    }
    public function show($id){
       return Inertia::render('CCO/Show',[
        'application' => $id,
       ]);
    }
}
