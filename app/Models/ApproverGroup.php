<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApproverGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relationship: Group has many approvers (many-to-many).
     */
    public function approvers()
    {
        return $this->belongsToMany(Approver::class, 'approver_group_approver', 'approver_group_id', 'approver_id')
                    ->withPivot('sequence')
                    ->orderBy('pivot_sequence', 'asc');
    }
}
