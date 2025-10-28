<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($request->is('login') || $request->is('logout') || $request->is('sanctum/csrf-cookie')) {
            return $next($request);
        }

        if (!$user) {
            return redirect('/login');
        }

        $details = $user->details;

        // Super Admin — access dashboard only
        if ($details) {
            $isSuperAdmin = (
                $details->department_id == 9 &&
                $details->division_id == 3 &&
                $details->role_id == 1 &&
                $details->permission_id == 1
            );

            $isSezadUser = ($details->department_id == 12);

            // Super admin: allow both dashboard & sezad
            if ($isSuperAdmin) {
                if (!$request->is('dashboard') && !$request->is('sezad')) {
                    return redirect('/dashboard');
                }
            }
            // SEZAD user: allow sezad only
            elseif ($isSezadUser) {
                if (!$request->is('sezad') && !$request->is('sezad/*')) {
                    return redirect('/sezad');
                }
            }
        }

        return $next($request);
    }
}
