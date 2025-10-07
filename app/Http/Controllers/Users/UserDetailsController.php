<?php

namespace App\Http\Controllers\Users;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetails\UserDetails;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangePasswordMail;

class UserDetailsController extends Controller
{
    public function index(Request $request)
    {
       $status       = $request->input('status', null);
        $departmentId = $request->input('department_id', null);
        $divisionId   = $request->input('division_id', null);
        $offset       = $request->input('offset', 0);
        $limit        = $request->input('limit', 10);

        // Call stored procedure
        $users = DB::select("
            CALL GetUsersWithFullDetails(?, ?, ?, ?, ?)
        ", [
            $status,
            $departmentId,
            $divisionId,
            $offset,
            $limit
        ]);

        // Return as JSON
        
         return Inertia::render('users/UsersDashboard', [
            'users' => $users]);
    }
    
    public function store(Request $request)
    {
        //dd($request);
        $validated = $request->validate([   
            //'users_id'   => 'required|string|max:50',
            'employee_id'   => 'required|string|max:50',
            //'email' => 'required|email|unique:user_details,email_address',
            'email_address' => 'required|email|max:100',
            'first_name'    => 'required|string|max:100',
            'middle_name'   => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'suffix'        => 'nullable|string|max:20',            
            'status'        => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'division_id'   => 'nullable|exists:divisions,id',
            'role_id'       => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
            'position'      => 'required|string|max:100',
            'birth_date'    => 'required|date',
            'sex'           => 'required|string|in:Male,Female',
            'phone'         => 'required|string|max:20',
            'address'   => 'required|array',
        ]);

        // $user = UserDetails::create($validated);

        // return response()->json([
        //     'message' => 'User created successfully!',
        //     'data' => $user,
        // ], 201);
        
       
        return redirect()->back()->with('success', 'User saved successfully!');
    }
    public function sendChangePassword()
    {
        $data = ['name' => 'John Paul Arce',
                 'temp_password' => 'Temp@1234',
                 'change_password_link' => 'http://localhost/settings/password'];

        try {
            Mail::to('johnpaularce.jhmc@gmail.com')->send(new ChangePasswordMail($data));

            return response()->json(['message' => 'Email sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
