<?php

namespace Swatkins\Approvals\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{

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
