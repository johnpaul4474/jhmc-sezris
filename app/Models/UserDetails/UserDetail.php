<?php

namespace App\Models\UserDetails;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{

    protected $table = 'user_details';  
    protected $fillable = [
        'id',
        'users_id',
        'employee_id',
        'email',
        'is_active',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'status',
        'department_id',   
        'division_id',
        'role_id',
        'permission_id',
        'birth_date',
        'location_id',
        'created_at',
        'updated_at'
    ];
}
