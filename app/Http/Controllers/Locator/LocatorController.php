<?php

namespace App\Http\Controllers\Locator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Locator\ApplicationModel;
use Illuminate\Support\Facades\Auth;
use App\Models\Locator\ApplicationForApproval;
use App\Helpers\AppConstants;
use App\Models\Locator\ApproverGroupApprover;
use App\Models\User;
use App\Models\ATO\AtoApplication;
use Illuminate\Support\Facades\Gate;
use App\Models\Signup\TemporaryUser;
use Illuminate\Support\Facades\Log;
use App\Models\UserDetails\UserDetail;


class LocatorController extends Controller
{
    public function index(){
        
         $user = auth()->user();
    
  if (Gate::denies('access-locator')) {
            abort(403, 'Unauthorized');
        }
       
       $AppForapprovals = $user->approvals;
       
         $applications = auth()->user()?->applications ?? [];
        return Inertia::render('Locator/Index', [
            'applications' => $AppForapprovals,
        ]);
    }
    
    public function show(String $id){


        if (Gate::denies('access-locator')) {
            abort(403, 'Unauthorized');
        }
       $application = ApplicationModel::with(['articleDetails', 'uploads', 'selections'])
    ->where('id', $id)
    ->first();
    
       return Inertia::render('Locator/View',[
        'app' => $application,
       ]);
    }
    public function serviProviderRequest(){
        $user = auth()->user();

        $tempUsers = TemporaryUser::where('locator', '')
    }
    public function vendorRequest()
    {
        $user = auth()->user();

    // Get all vendors where locator column contains the user's email
    $tempUsers = TemporaryUser::where('locator', 'like', '%' . $user->email . '%')
        ->where('status','like','%new%')
        ->get();
        
            return Inertia::render('Locator/Vendor/VendorRequest',[
            'vendor' => $tempUsers,
            ]);
    }
    public function approveVendorRequest($id)
    {
        $user = auth()->user();
        $vendor = TemporaryUser::findOrFail($id);
       if (User::where('email', $vendor->email)->exists()) {
    abort(422, 'Email already exists');
}
        $newVendorUser = User::create([
        'name' => $vendor->name,
        'email' => $vendor->email,
        'password' => $vendor->temp_password,
        'created_at'=> now(),// date of vendor was verified
        'updated_at' =>now(),
     
    ]);

    // Create associated UserDetails if needed
    $userDetails = UserDetail::create([
        'user_id' => $newVendorUser->id,
        'email' => $newVendorUser->email,
        'status' => 1,
        'first_name'=> $newVendorUser->name,
        'role_id' => 7,
        'permission_id' => 2,
        'created_at'=> now(),
        'upddated_at' =>now(),
        // map other details from TemporaryUser as needed
    ]);
    $vendor->status ='approved';
    $vendor->save();

        Log::info('User:'.$user->name.'Approved vendor: ', $vendor->toArray());
        return back()->with('success', 'Vendor Approved successfully');
    }
    public function myVendors(){
        $user = auth()->user();
        $tempUsers = TemporaryUser::where('locator', 'like', '%' . $user->email . '%')
        ->where('status','like','%approved%')
        ->where('business_type', 4)
        ->get();
        return Inertia::render('Locator/Vendor/MyVendor',[
            'vendor'=>$tempUsers,
        ]);
    }
    
}

