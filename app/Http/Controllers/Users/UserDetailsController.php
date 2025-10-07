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
    public function sendTestEmail()
{
    $token = Session::get('google_token');

    if (!$token) {
        return redirect('/auth/google'); // Redirect to login if no token
    }

    $client = new Client();
    $client->setClientId(config('services.google.client_id'));
    $client->setClientSecret(config('services.google.client_secret'));
    $client->setRedirectUri(config('services.google.redirect'));
    $client->addScope('https://www.googleapis.com/auth/gmail.send');
    $client->setAccessToken($token);

    if ($client->isAccessTokenExpired()) {
        return redirect('/auth/google'); // Re-authenticate
    }

    $gmail = new Gmail($client);

    $to = 'johnpaularce.jhmc@gmail.com'; // Change this to a valid email
    $subject = 'Test Email from Laravel + Gmail API';
    $body = '<p>This is a test email sent via Gmail API using OAuth2 in Laravel.</p>';

    $rawMessage = "To: $to\r\n";
    $rawMessage .= "Subject: $subject\r\n";
    $rawMessage .= "MIME-Version: 1.0\r\n";
    $rawMessage .= "Content-Type: text/html; charset=utf-8\r\n\r\n";
    $rawMessage .= $body;

    $encodedMessage = new Message();
    $encodedMessage->setRaw(rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '='));

    try {
        $gmail->users_messages->send('me', $encodedMessage);
        return redirect()->back()->with('success', 'Test email sent successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Failed to send email: ' . $e->getMessage()]);
    }
}
}
