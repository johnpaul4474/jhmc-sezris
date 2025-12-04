<?php

namespace App\Models\Signup;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryUser extends Model
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
        'temp_password',
    ];

    protected $casts = [
        'locator' => 'array',   // JSON column
    ];
}
