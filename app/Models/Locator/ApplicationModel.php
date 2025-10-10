<?php

namespace App\Models\Locator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class ApplicationModel extends Model
{
    use HasFactory;

    protected $table = 'application_forms';

    protected $fillable = [
        'form_title',
        'user_id',
        'control_number',
        'form_number',
    ];

    protected static function booted()
    {
        static::creating(function ($application) {
            // ✅ Generate control_number (YYYY-MM-XXX)
            // $prefix = now()->format('Y-m');

            // $count = self::whereYear('created_at', now()->year)
            //             ->whereMonth('created_at', now()->month)
            //             ->count() + 1;

            // $application->control_number = $prefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);

            // ✅ Generate form_number based on title (acronym + XXX)
            $acronym = strtoupper(
    collect(explode(' ', $application->form_title))
        ->map(fn($word) => mb_substr($word, 0, 1))
        ->implode('')
);

$counter = 1;
do {
    $formNumber = $acronym . '-' . str_pad($counter, 4, '0', STR_PAD_LEFT);
    $exists = self::where('form_number', $formNumber)->exists();
    $counter++;
} while ($exists);

$application->form_number = $formNumber;
        });
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function articleDetails()
    {
        return $this->hasMany(\App\Models\Locator\ArticleDetail::class, 'application_form_id');
    }
    public function uploads()
    {
    return $this->hasMany(\App\Models\Locator\Upload::class, 'application_form_id');
    }
    public function selections()
    {
    return $this->hasMany(\App\Models\Locator\UserApplicationSelection::class, 'application_id');
    }
}
