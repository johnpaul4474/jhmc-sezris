<?php
namespace App\Http\Controllers\OSAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;
use Illuminate\Support\Facades\Gate;
class OsacController extends Controller
{
   
   public function index()
   {
   
  if (Gate::denies('access-osac')) {
            abort(403, 'Unauthorized');
        }
      $applications = ApproverGroupApprover::with('application')
    ->where('approver_id', auth()->id())
    ->get();
      return Inertia::render('OSAC/Index',[
         'applications'=> $applications,
      ]);
    
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
