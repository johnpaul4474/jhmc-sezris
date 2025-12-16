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
use App\Models\Signup\SignupApprover;


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
    public function serviceProviderRequest()
    { 
        $user = auth()->user();
         $status= "new";
        $tempUsers = TemporaryUser::where('locator', 'like','%'. $user->email .'%')
                                    ->where('business_type', 1)                
                                    ->where('status','like','%'.$status.'%')
                                    ->get();
                      
                return Inertia::render('Locator/ServiceProvider/ServiceProviderRequest',[
                   'serviceProvider' => $tempUsers,  
                ]);
    }
    
    public function approveServiceProviderRequest($id)
    { $user = auth()->user();
        $serviceprovider =  TemporaryUser::findOrFail($id);
        if (User::where('email', $serviceprovider->email)->exists()) {
    abort(422, 'Email already exists');
}      
      $newServiceProviderUser = User::create([
        'name' => $serviceprovider->name,
        'email' => $serviceprovider->email,
        'password' => $serviceprovider->temp_password,
        'created_at'=> now(),// date of vendor was verified
        'updated_at' =>now(),
      ]);
      $userDetails = UserDetail::create([
        'user_id' => $newServiceProviderUser->id,
        'email' => $newServiceProviderUser->email,
        'status' => 1,
        'first_name'=> $newServiceProviderUser->name,
        'role_id' => 4,
        'permission_id' => 2,
        'created_at'=> now(),
        'upddated_at' =>now(),
        
    ]);
   
    $serviceprovider->status ='approved';
    $serviceprovider->save();
     }
 

    public function MyServiceProviders()
    { 
        $user = auth()->user();
        $tempUsers = TemporaryUser::where('locator', 'like', '%' . $user->email . '%')
        ->where('status','like','%approved%')
        ->where('business_type', 1)
        ->get();
        return Inertia::render('Locator/ServiceProvider/MyServiceProvider',[
                   'serviceProvider' => $tempUsers,  
                ]);
    }

    
    public function myVendors(){
        $user = auth()->user();

       $myvendors = SignupApprover::with('temporary_user') // make sure this relationship exists
    ->where('approver_id', $user->id)
    ->where('status', 'Approved')
    ->get();
         
      
    return Inertia::render('Locator/Vendor/MyVendor', [
        'vendor' => $myvendors,
    ]); 
      
    }
    public function approveVendorRequest($id)
{ 
    $user = auth()->user();
    $vendor = TemporaryUser::findOrFail($id);

    // Get approver record (not exists)
    $approver = SignupApprover::where('temporary_user_id', $vendor->id)
        ->where('approver_id', $user->id)
        ->first();
     
    // Already approved → STOP
    if ($approver && $approver->status === 'Approved') {
        return back()->with('error', 'You already approved this vendor');
    }

    // Update existing approver
    if ($approver) {
        $approver->update([
            'status' => 'Approved',
            'remark' => 'update',
            'approved_at' => now(),
        ]);
        
    }
    
    // Create new approver
    else {
        SignupApprover::create([
            'temporary_user_id' => $vendor->id,
            'approver_id' => $user->id,
            'status' => 'Approved',
            'remark' => 'approved',
            'approved_at' => now(),
        ]);
        dd('vendor requesting for Approval to Locator');
    }

    /**
     * ✅ Create actual user ONLY IF NOT EXISTS
     */
    if (!User::where('email', $vendor->email)->exists()) {

        $newVendorUser = User::create([
            'name' => $vendor->name,
            'email' => $vendor->email,
            'password' => $vendor->temp_password,
        ]);

        UserDetail::create([
            'user_id' => $newVendorUser->id,
            'email' => $newVendorUser->email,
            'status' => 1,
            'first_name' => $newVendorUser->name,
            'role_id' => 7,
            'permission_id' => 2,
        ]);

        // $vendor->update([
        //     'status' => 'approved',
        // ]);
    }

    Log::info(
        'User '.$user->name.' approved vendor',
        ['vendor_id' => $vendor->id]
    );

    return back()->with('success', 'Vendor approved successfully');
}
public function vendorRequest()
    {
        $user = auth()->user();

    // Get all vendors where locator column contains the user's email
    $tempUsers = TemporaryUser::where('locator', 'like', '%' . $user->email . '%')
        ->where('status','like','%new%')
        ->get();
       // dd($tempUsers);
            return Inertia::render('Locator/Vendor/VendorRequest',[
            'vendor' => $tempUsers,
            
            ]);
    }
    
}

