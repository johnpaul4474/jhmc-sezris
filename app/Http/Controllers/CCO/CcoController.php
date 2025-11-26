<?php

namespace App\Http\Controllers\CCO;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;
use App\Models\User;

class CcoController extends Controller
{
    public function index(){
        $user = auth()->user();
   $isCCO =
            optional($user->details)->position_id === 37 &&
            optional($user->details)->department_id === 12 &&
            optional($user->details)->role_id === 2 &&
            optional($user->details)->permission_id === 2;
            if($isCCO){
                $applications = ApproverGroupApprover::with('application')
                            ->where('approver_id', auth()->id())
                            ->get();
                                return Inertia::render('CCO/Index',[
                                    'applications'=> $applications,
                                ]);
            }else{
                return redirect()->route('dashboard');
            }
       
    }
    public function show($id){
       return Inertia::render('CCO/Show',[
        'application' => $id,
       ]);
    }
}
