<?php

namespace App\Models\UserDetails;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{

    protected $table = 'location';  
    protected $fillable = [
        'id',
        'region_id',
        'province_id',
        'municipality_id',
        'barangay_id',  
        'created_at',
        'updated_at'
    ];
}
