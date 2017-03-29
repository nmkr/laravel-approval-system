<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;
use Swatkins\Approvals\Exceptions\ModelNotInstanceOfApproverModelException;
use Swatkins\Approvals\Exceptions\UserDoesNotHaveApprovalAuthorityException;

class Approval extends Model
{

    protected $fillable = [
        'requester_id',
        'approver_id',
        'approvable_type',
        'approvable_id',
        'approved',
        'last_activity',
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

    public function review($approved = false, $message = "", $approver = null)
    {
        $approver = $approver ?: auth()->user();
        $approverModel = config('approvals.approver_model');
        if (!$approver instanceof $approverModel) {
            throw new ModelNotInstanceOfApproverModelException;
        }
        if (!$approver->canApprove($this)) {
            throw new UserDoesNotHaveApprovalAuthorityException;
        }
        $review = Review::create([
            'author_id'   => (integer)$approver->id,
            'approval_id' => (integer)$this->id,
            'body'        => $message,
            'approved'    => (boolean)$approved,
        ]);
        $review->load(['author', 'approval']);

        return $review;
    }

    public function setApprovedAttribute($value)
    {
        $this->attributes['approved'] = (boolean)$value;
    }

    public function getApprovedAttribute()
    {
        return (boolean)$this->attributes['approved'];
    }
}
