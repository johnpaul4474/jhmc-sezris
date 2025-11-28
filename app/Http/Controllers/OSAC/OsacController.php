<?php
namespace App\Http\Controllers\OSAC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApproverGroupApprover;
use Illuminate\Support\Facades\Gate;
use App\Models\Locator\ApplicationModel;
class OsacController extends Controller
{
   
   public function index()
   { $user= auth()->user();
   
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
      $user= auth()->user();
      if (Gate::denies('access-osac')) {
            abort(403, 'Unauthorized');
        }
         $application = ApplicationModel::with(['articleDetails', 'uploads', 'options','selections','approval'])
                     ->where('id', $id)
                     ->first();
          $approver = ApproverGroupApprover::where('approver_group_id', $application->approval->approver_group_id)
                  ->where('approver_id', $user->id)
                  ->where('application_form_id', $application->id)
                  ->first();
       return Inertia::render('OSAC/Show',[
         'application' => $application,
         'approver_status' => $approver->status,
       ]);
    }
}
