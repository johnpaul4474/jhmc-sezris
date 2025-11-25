<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       $user = $request->user();
      $isOSACProcessor = $user->details->position_id === 36 && //osac
                         $user->details->department_id === 12 && // sezad
                         $user->details->user_function_id === 4 ;//One Stop Action center
       if( $isOSACProcessor){ 
        return redirect('/osac');    
       }
        return $next($request);
    }
}
