<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
    return $this->belongsToMany(User::class, 'approver_group_approver', 'approver_group_id', 'approver_id')
                ->withPivot('role', 'sequence', 'status', 'acted_at')
                ->orderBy('pivot_sequence', 'asc');
}
    public function allApproversStatusApproved(): bool
    {
        return $this->approvers->every(fn($a) => $a->pivot->status === 'Approved' );
    }
}
