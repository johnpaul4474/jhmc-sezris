<?php

namespace App\Models\Locator;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ApproverGroupApprover extends Pivot
{
    protected $table = 'approver_group_approver';

    // If your pivot has timestamps
    public $timestamps = true;

    // Mass assignable columns
    protected $fillable = [
        'approver_group_id',
        'approver_id',
        'sequence',
        'application_form_id',
    ];

    /**
     * The approver group this pivot belongs to.
     */
    public function approverGroup()
    {
        return $this->belongsTo(ApproverGroup::class, 'approver_group_id');
    }

    /**
     * The approver (user) linked to this pivot.
     */
    public function approver()
    {
        return $this->belongsTo(\App\Models\User::class, 'approver_id');
    }
}
