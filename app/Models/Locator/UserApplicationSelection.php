<?php

namespace App\Models\Locator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApplicationSelection extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'option_id',
        'selected_at',
        'amount',
    ];

    /**
     * Relationships
     */

    public function application()
    {
        return $this->belongsTo(\App\Models\Locator\ApplicationModel::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function option()
    {
        return $this->belongsTo(\App\Models\Locator\ApplicationOption::class, 'option_id');
    }
}
