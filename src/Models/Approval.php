<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{

    protected $fillable = [
        'requester_id',
        'approver_id',
        'approvable_type',
        'approvable_id',
        'approved',
        'last_activity'
    ];

    public function requester()
    {
        return $this->belongsTo(config('approvals.requester_model'), 'requester_id');
    }

    public function approver()
    {
        return $this->belongsTo(config('approvals.approver_model'), 'approver_id');
    }

    /**
     * Get all of the owning approvable models.
     */
    public function approvable()
    {
        return $this->morphTo();
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
