<?php

namespace App\Models\Locator;

use Illuminate\Database\Eloquent\Model;

class ApplicationOption extends Model
{
    protected $fillable = ['name', 'validity'];

    public function selections()
    {
        return $this->hasMany(UserApplicationSelection::class, 'article_option_id');
    }
}
