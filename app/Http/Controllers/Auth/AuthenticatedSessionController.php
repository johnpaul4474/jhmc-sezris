<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\DB;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $request->validateCredentials();
        $user_details = DB::table('user_details')
            ->where('user_id', $user->id)
            ->first();
        $request->session()->put('user_details', $user_details);    
        
        if (Features::enabled(Features::twoFactorAuthentication()) && $user->hasEnabledTwoFactorAuthentication()) {
            $request->session()->put([
                'login.id' => $user->getKey(),
                'login.remember' => $request->boolean('remember'),
            ]);

            return to_route('two-factor.login');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();
        if($user_details && $user_details->department_id == 9
                && $user_details->division_id == 3
                && $user_details->role_id == 1
                && $user_details->permission_id == 1)
        {
            return redirect()->intended(route('dashboard', absolute: false));
            
        }else if($user_details && $user_details->role_id == 3){
            return redirect()->intended('/locator');
        }else if($user_details && $user_details->position_id == 36 && $user_details->department_id == 12) {
            return redirect()->intended('sezad/osac');
        }elseif ($user_details && $user_details->department_id == 12
                 && $user_details->position_id == 37  &&
                 $user_details->role_id == 2 
                 && $user_details->permission_id == 2 ){
            return redirect()->intended('sezad/cco');
        }else if($user_details && $user_details->department_id == 10 
                && $user_details->role_id == 2
                && $user_details->position_id = 53
                && $user_details->permission_id == 2){
               return redirect()->intended('fsd/finance');
    }else if($user_details && $user_details->department_id == 12
                && $user_details->user_function_id == 5
                && $user_details->role_id == 2
                && $user_details->permission_id == 1)
        {
            return redirect()->intended('sezad/manager');
        }else if($user_details && $user_details->department_id == 12
                && $user_details->division_id == null
                && $user_details->role_id == 2
                && $user_details->permission_id == 2)
        {
            return redirect()->intended('/sezad');
        }else{
            return redirect()->intended(route('dashboard', absolute: false));
        }
         
        
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    { 
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
