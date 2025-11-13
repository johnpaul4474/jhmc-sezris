<?php

namespace App\Http\Controllers\BDD;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class BDDController extends Controller
{
    public function index()
    {  $users = User::query()->paginate(3);
        $user = Auth::user();
        if ($user instanceof \App\Models\User) { 
            if ($user && method_exists($user, 'load')) {
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
                ]);
            }
        }
        return Inertia::render('bdd/BddDashboard', [
            'user' => $user,
            'users'=> $users,
        ]);
    }
}