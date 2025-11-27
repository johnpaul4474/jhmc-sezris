<?php
namespace App\Http\Controllers\OSAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;

class OsacController extends Controller
{
   
   public function index()
   {
   $user = auth()->user();
   $isOSAC =
            optional($user->details)->position_id === 36 &&
            optional($user->details)->department_id === 12 &&
            optional($user->details)->role_id === 2 &&
            optional($user->details)->permission_id === 2;
    if($isOSAC){
      $applications = ApproverGroupApprover::with('application')
    ->where('approver_id', auth()->id())
    ->get();
      return Inertia::render('OSAC/Index',[
         'applications'=> $applications,
      ]);
    }else{
     abort(401, 'Unauthorized.');
    }
   }
   public function create(){
      return Inertia::render('OSAC/Create',[]);
   }
   public function show($id){

       return Inertia::render('OSAC/Show',[
         'application' => $id,
       ]);
    }
}
