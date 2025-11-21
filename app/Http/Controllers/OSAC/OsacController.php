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
}
