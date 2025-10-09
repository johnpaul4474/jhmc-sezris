<?php

namespace App\Http\Controllers\Users;

use Illuminate\Validation\Rules;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ChangePasswordMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\UserDetails\Position;
use App\Models\Location\{
    Region,
    Province,
    Municipality,
    Barangay,
    Street,
    Location
};

use App\Models\User;

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
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {

        //dd($request->all());
        $validated = $request->validate([
            'employee_id'   => 'required|string|max:50',
            'email_address' => 'required|email|max:100',
            'first_name'    => 'required|string|max:100',
            'middle_name'   => 'required|string|max:100',
            'last_name'     => 'required|string|max:100',
            'suffix'        => 'nullable|string|max:20',
            'status'        => 'required|numeric|in:0,1',
            'department_id' => 'nullable|exists:departments,id',
            'division_id'   => 'nullable|exists:divisions,id',
            'role_id'       => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
            'position'      => 'required|string|max:100',
            'birth_date'    => 'required|date',
            'sex'           => 'required|string|in:Male,Female',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $tempPassword = Str::random(8);

            $full_name = trim("{$validated['first_name']} {$validated['middle_name']} {$validated['last_name']}" .
                ($validated['suffix'] ? ", {$validated['suffix']}" : ""));

            $user = User::create([
                'name' => $full_name,
                'email' => $validated['email_address'],
                'password' => $tempPassword,
            ]);

            $position = Position::create([
                'position_name' => $validated['position'],
            ]);

            $locationId = $this->storeLocation($request);

            $user->details()->create([
                'user_id'        => $user->id,
                'employee_id'    => $validated['employee_id'],
                'email'          => $validated['email_address'],
                'status'         => $validated['status'],
                'first_name'     => $validated['first_name'],
                'middle_name'    => $validated['middle_name'],
                'last_name'      => $validated['last_name'],
                'suffix'         => $validated['suffix'],
                'position_id'    => $position->id,
                'sex'            => $validated['sex'],
                'department_id'  => $validated['department_id'],
                'division_id'    => $validated['division_id'],
                'role_id'        => $validated['role_id'],
                'permission_id'  => $validated['permission_id'],
                'location_id'    => $locationId,
                'birth_date'     => $validated['birth_date'],
            ]);

            $this->sendChangePassword($full_name, $tempPassword, $validated['email_address']);
            DB::commit();

            return response('success', 200);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Store user failed: ' . $e->getMessage());
            return response('error', 500);
        }
    }

    public function storeLocation(Request $request)
    {
        $address = $request->address;
        //dd($address['street']);
        DB::beginTransaction();
        try{
        $region = Region::create([
            'code' => json_decode($address['region'])->code,
            'name' => json_decode($address['region'])->name,
        ]);

        $province = Province::create([
            'code' => json_decode($address['province'])->code,
            'name' => json_decode($address['province'])->name,
            'region_id' => $region->id,
        ]);

        $municipality = Municipality::create([
            'code' => json_decode($address['municipality'])->code,
            'name' => json_decode($address['municipality'])->name,
            'province_id' => $province->id,
        ]);

        $barangay = Barangay::create([
            'code' => json_decode($address['barangay'])->code,
            'name' => json_decode($address['barangay'])->name,
            'municipality_id' => $municipality->id,
        ]);

        $street = Street::create([
            'street_name' => $address['street'],
            'barangay_id' => $barangay->id,
        ]);
        DB::commit();

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Address user failed: ' . $e->getMessage());
            return response('error', 500);
        }

        DB::beginTransaction();
        try{
        $location = Location::create([
            'region_id' => $region->id,
            'province_id' => $province->id,
            'municipality_id' => $municipality->id,
            'barangay_id' => $barangay->id,
            'street_id' => $street->id,
        ]);
        DB::commit();
         } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Location user failed: ' . $e->getMessage());
            return response('error', 500);
        }
        return $location->id;
    }

    public function sendChangePassword($name, $tempPassword, $email)
    {
        $data = [
            'name' => $name,
            'temp_password' => $tempPassword,
            'change_password_link' => 'http://localhost/settings/password?temp_password='.$tempPassword,
        ];

        try {
            Mail::to($email)->send(new ChangePasswordMail($data));
        } catch (\Exception $e) {
            Log::warning('Email failed to send: ' . $e->getMessage());
        }
    }
}
