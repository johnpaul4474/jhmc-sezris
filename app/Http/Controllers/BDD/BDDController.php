<?php

namespace App\Http\Controllers\BDD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Locator\LocatorModel;
use App\Models\UserDetails\Userdetail;
use Illuminate\Support\Facades\Schema;
use App\Http\Requests\SaveLocatorProfileRequest;

class BDDController extends Controller
{
    public function index()
{
    // 1. Get all users that have a locator profile
    $registered_locators = User::has('profile')->with('profile')->get();

    // 2. Paginate all users (without filtering)
    $users = User::paginate(3);

    // 3. Load details for the authenticated user
    $user = Auth::user();
    if ($user instanceof \App\Models\User && method_exists($user, 'load')) {
        $user->load([
            'details' => function ($query) {
                $query->select(
                    'id',
                    'user_id',
                    'permission_id',
                    'role_id',
                    'department_id',
                    'division_id',
                    'user_function_id'
                );
            },
            'profile', // optional: also eager-load the profile for current user
        ]);
    }

    // 4. Return Inertia
    return Inertia::render('bdd/BddDashboard', [
        'user'                => $user,
        'users'               => $users,
        'registered_locators' => $registered_locators,
    ]);
}

    public function locators()
    {
       $locator = User::with('profile')->find(86);
       dd($locator);
    }

    public function saveLocatorProfile(SaveLocatorProfileRequest $request)
    {
            $data = $request->validated();

            /* 1. Create User */
            $user = User::create([
                'name'     => $data['owner_name'],
                'email'    => $data['official_email_gmail'],
                'password' => bcrypt('password123'),
            ]);
             $data['user_id'] = $user->id;
            /* 2. Create User Details */
            UserDetail::create([
                'user_id'   => $user->id,
                'first_name' => $data['owner_name'],
                'email'      => $data['company_email'],
                'role_id'    => 3,
            ]);

            /* 3. Add user_id to validated data before saving locator */
            $data['user_id'] = $user->id;

            /* 4. Create Locator Profile using $data */
            $locator = LocatorModel::create($data);
           //if successful Sezris should send temporary Login Credetials(username and temporary password) to the Locator
        Log::info('Sending Email to:', ['user Email: ' => $user->email ,"Usernme" =>$user->email, "Temporary password"=> 'password123']);
            return response()->json([
                'success' => true,
                'user'    => $user,
                'locator' => $locator,
            ]);
    }

}