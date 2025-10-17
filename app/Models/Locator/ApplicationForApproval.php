<?php

namespace App\Models\Locator;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ApproverGroup;
use App\Models\User;
use App\Models\Locator\ApplicationModel;
use App\Models\Locator\ApproverGroupApprover;


class ApplicationForApproval extends Model
{
    use HasFactory;

    protected $table = 'application_for_approval';

    protected $fillable = [
        'application_id',
        'approver_group_id',
        'approver_id',
        'status',
        'remark',
        'acted_at',
    ];

    protected $casts = [
        'acted_at' => 'datetime',
    ];

    // 🔗 Relationship to the application (the actual transaction/request)
    public function application()
    {
        return $this->belongsTo(ApplicationModel::class);
    }

    // 🔗 Relationship to the approver group
    public function approverGroup()
    {
        return $this->belongsTo(ApproverGroup::class, 'approver_group_id');
    }

    // 🔗 Relationship to the user (approver)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
