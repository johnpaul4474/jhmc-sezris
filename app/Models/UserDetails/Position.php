<?php

namespace App\Models\UserDetails;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{

    protected $table = 'positions';  
    protected $fillable = [
        'id',
        'name',
        'created_at',
        'updated_at'
    ];
}
