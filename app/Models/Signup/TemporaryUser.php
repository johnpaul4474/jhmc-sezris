<?php

namespace App\Models\Signup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TemporaryUser extends Authenticatable
{
    use HasFactory;

    protected $table = 'temporary_users';

    protected $fillable = [
        'email',
        'name',
        'business_type',
        'locator',
        'status',
        'remark',
        'temp_password', // this is your password field
    ];

    protected $casts = [
        'locator' => 'array',   // JSON column
    ];

    // If your password column is 'temp_password', tell Laravel to use it
    public function getAuthPassword()
    {
        return $this->temp_password;
    }
}
